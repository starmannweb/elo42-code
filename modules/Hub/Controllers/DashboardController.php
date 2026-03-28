<?php

declare(strict_types=1);

namespace Modules\Hub\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;
use App\Models\Product;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\User;
use DateTimeImmutable;

class DashboardController extends Controller
{
    private const IA_CREDIT_COST = 1;

    public function index(Request $request): void
    {
        $context = $this->buildBaseContext('Dashboard', 'dashboard');

        $this->view('hub/dashboard', array_merge($context, [
            'pageTitle'        => 'Hub — Elo 42',
            'showcaseItems'    => $this->buildShowcaseItems(),
            'siteBuilderAccess'=> $this->resolveSiteBuilderAccess($context['organization']),
            'iaCredits'        => $this->resolveIaCredits($context['organization'], $context['user']),
            'iaCreditCost'     => self::IA_CREDIT_COST,
        ]));
    }

    public function vitrine(Request $request): void
    {
        redirect('/hub/#vitrine');
    }

    public function sites(Request $request): void
    {
        $context = $this->buildBaseContext('Meus Sites', 'sites');
        $access = $this->resolveSiteBuilderAccess($context['organization']);

        $this->view('hub/sites', array_merge($context, [
            'pageTitle'         => 'Meus Sites — Hub Elo 42',
            'siteBuilderAccess' => $access,
        ]));
    }

    public function expositorIa(Request $request): void
    {
        $context = $this->buildBaseContext('Expositor IA', 'expositor');
        $credits = $this->resolveIaCredits($context['organization'], $context['user']);

        $this->view('hub/expositor-ia', array_merge($context, [
            'pageTitle'     => 'Expositor IA — Hub Elo 42',
            'iaCredits'     => $credits,
            'iaCreditCost'  => self::IA_CREDIT_COST,
            'canGenerateIa' => $credits >= self::IA_CREDIT_COST,
        ]));
    }

    public function creditos(Request $request): void
    {
        $context = $this->buildBaseContext('Créditos', 'creditos');
        $credits = $this->resolveIaCredits($context['organization'], $context['user']);

        $packages = [
            ['name' => 'Pacote Inicial', 'credits' => 20, 'price' => 'R$ 37,00'],
            ['name' => 'Pacote Operacional', 'credits' => 60, 'price' => 'R$ 97,00', 'badge' => 'Mais vendido'],
            ['name' => 'Pacote Intensivo', 'credits' => 150, 'price' => 'R$ 197,00'],
        ];

        $this->view('hub/creditos', array_merge($context, [
            'pageTitle' => 'Créditos — Hub Elo 42',
            'iaCredits' => $credits,
            'packages'  => $packages,
        ]));
    }

    public function suporte(Request $request): void
    {
        $context = $this->buildBaseContext('Suporte', 'suporte');

        $this->view('hub/suporte', array_merge($context, [
            'pageTitle' => 'Suporte — Hub Elo 42',
        ]));
    }

    public function configuracoes(Request $request): void
    {
        $context = $this->buildBaseContext('Configurações', 'configuracoes');
        $subscription = $this->resolveSiteBuilderAccess($context['organization']);
        $credits = $this->resolveIaCredits($context['organization'], $context['user']);

        $this->view('hub/configuracoes', array_merge($context, [
            'pageTitle'         => 'Configurações — Hub Elo 42',
            'siteBuilderAccess' => $subscription,
            'iaCredits'         => $credits,
        ]));
    }

    private function buildBaseContext(string $breadcrumb, string $activeMenu): array
    {
        $user = Session::user() ?? [];
        $organization = Session::get('organization');
        $organization = is_array($organization) ? $organization : null;

        $hasOrg = is_array($organization) && !empty($organization['id']);
        if (!$hasOrg && !empty($user['id'])) {
            try {
                if (User::hasOrganization((int) $user['id'])) {
                    $organization = User::getOrganization((int) $user['id']);
                    if ($organization) {
                        Session::set('organization', [
                            'id'        => $organization['id'],
                            'name'      => $organization['name'],
                            'slug'      => $organization['slug'] ?? '',
                            'type'      => $organization['type'] ?? '',
                            'plan'      => $organization['plan'] ?? 'trial',
                            'status'    => $organization['status'] ?? 'trial',
                            'role_slug' => $organization['role_slug'] ?? null,
                            'role_name' => $organization['role_name'] ?? null,
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                Session::flash('warning', 'A plataforma está em modo de contingência. Alguns módulos podem ficar indisponíveis temporariamente.');
            }
        }

        $firstName = explode(' ', (string) ($user['name'] ?? 'Usuário'))[0] ?? 'Usuário';
        $greeting = match (true) {
            (int) date('H') < 12  => 'Bom dia',
            (int) date('H') < 18  => 'Boa tarde',
            default               => 'Boa noite',
        };

        return [
            'user'                 => $user,
            'organization'         => $organization,
            'firstName'            => $firstName,
            'greeting'             => $greeting,
            'breadcrumb'           => $breadcrumb,
            'activeMenu'           => $activeMenu,
            'organizationDeadline' => $this->resolveOrganizationDeadline($user, $organization),
            'supportEmail'         => 'suporte@elo42.com.br',
            'supportWhatsapp'      => '(13) 97800-8047',
            'supportWhatsappUrl'   => 'https://wa.me/5513978008047',
        ];
    }

    private function resolveOrganizationDeadline(array $user, ?array $organization): array
    {
        if (!empty($organization['id'])) {
            return [
                'is_required' => false,
                'is_overdue'  => false,
                'days_left'   => null,
                'deadline_at' => null,
            ];
        }

        $createdAt = (string) ($user['created_at'] ?? '');
        if ($createdAt === '') {
            return [
                'is_required' => true,
                'is_overdue'  => false,
                'days_left'   => null,
                'deadline_at' => null,
            ];
        }

        try {
            $created = new DateTimeImmutable($createdAt);
            $deadline = $created->modify('+7 days');
            $now = new DateTimeImmutable('now');
            $diffSeconds = $deadline->getTimestamp() - $now->getTimestamp();
            $daysLeft = (int) ceil($diffSeconds / 86400);

            return [
                'is_required' => true,
                'is_overdue'  => $diffSeconds <= 0,
                'days_left'   => max(0, $daysLeft),
                'deadline_at' => $deadline->format('Y-m-d H:i:s'),
            ];
        } catch (\Throwable $e) {
            return [
                'is_required' => true,
                'is_overdue'  => false,
                'days_left'   => null,
                'deadline_at' => null,
            ];
        }
    }

    private function resolveSiteBuilderAccess(?array $organization): array
    {
        $default = [
            'has_org'               => !empty($organization['id']),
            'plan_name'             => 'Sem assinatura',
            'billing_cycle'         => null,
            'status'                => 'inactive',
            'status_label'          => 'Inativo',
            'monthly_fee_label'     => 'Consulte valores',
            'has_active_monthly'    => false,
            'can_publish'           => false,
            'publish_requirement'   => 'Para publicar um site no construtor, é necessário ter uma mensalidade ativa.',
        ];

        if (empty($organization['id'])) {
            $default['status_label'] = 'Pendente de organização';
            return $default;
        }

        try {
            $subscriptions = Subscription::where('organization_id', (int) $organization['id']);
            if (empty($subscriptions)) {
                $default['status_label'] = 'Sem assinatura ativa';
                return $default;
            }

            usort($subscriptions, static function (array $a, array $b): int {
                $aTime = strtotime((string) ($a['updated_at'] ?? $a['created_at'] ?? '1970-01-01'));
                $bTime = strtotime((string) ($b['updated_at'] ?? $b['created_at'] ?? '1970-01-01'));
                return $bTime <=> $aTime;
            });

            $current = $subscriptions[0];
            $status = (string) ($current['status'] ?? 'inactive');
            $billingCycle = (string) ($current['billing_cycle'] ?? '');
            $isActiveMonthly = $status === 'active' && $billingCycle === 'monthly';
            $planName = (string) ($current['plan_name'] ?? 'Plano');
            $price = isset($current['price']) ? (float) $current['price'] : 0.0;

            return [
                'has_org'               => true,
                'plan_name'             => $planName,
                'billing_cycle'         => $billingCycle,
                'status'                => $status,
                'status_label'          => $this->translateSubscriptionStatus($status),
                'monthly_fee_label'     => $this->formatMoney($price) . ($billingCycle === 'monthly' ? '/mês' : ''),
                'has_active_monthly'    => $isActiveMonthly,
                'can_publish'           => $isActiveMonthly,
                'publish_requirement'   => 'Para publicar um site no construtor, é necessário ter uma mensalidade ativa.',
            ];
        } catch (\Throwable $e) {
            return $default;
        }
    }

    private function resolveIaCredits(?array $organization, array $user): int
    {
        $fallback = (int) Session::get('hub_ia_credits', 0);

        try {
            $key = null;
            if (!empty($organization['id'])) {
                $key = 'ia_credits_org_' . (int) $organization['id'];
            } elseif (!empty($user['id'])) {
                $key = 'ia_credits_user_' . (int) $user['id'];
            }

            if ($key === null) {
                return $fallback;
            }

            $pdo = Database::connection();
            $stmt = $pdo->prepare("SELECT setting_value FROM platform_settings WHERE setting_key = :key LIMIT 1");
            $stmt->execute(['key' => $key]);
            $row = $stmt->fetch();

            if (!$row) {
                return $fallback;
            }

            return max(0, (int) ($row['setting_value'] ?? 0));
        } catch (\Throwable $e) {
            return $fallback;
        }
    }

    private function buildShowcaseItems(): array
    {
        $fallback = [
            ['icon' => 'book', 'title' => 'Expositor IA (por créditos)', 'description' => 'Geração de esboços e estudos com IA mediante consumo de créditos por uso.', 'price' => 'A partir de R$ 37,00', 'badge' => 'Novo', 'badge_type' => 'new', 'cta' => 'Ver detalhes', 'url' => url('/hub/expositor-ia')],
            ['icon' => 'monitor', 'title' => 'Painel de Gestão de Igrejas', 'description' => 'Sistema completo de gestão para membros, eventos e financeiro.', 'price' => 'R$ 97,00/mês', 'badge' => 'Mais vendido', 'badge_type' => 'hot', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'gift', 'title' => 'Google para ONGs', 'description' => 'Trilha guiada para aprovação e criação do Google Workspace gratuito.', 'price' => 'R$ 297,00', 'badge' => 'Novo', 'badge_type' => 'new', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'gift', 'title' => 'Google Ad Grants', 'description' => 'Implantação e aprovação do edital para receber até US$ 10.000/mês.', 'price' => 'R$ 497,00', 'badge' => '', 'badge_type' => '', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'megaphone', 'title' => 'Gestão de Tráfego Pago', 'description' => 'Estratégia e operação de campanhas para ampliar alcance e resultados.', 'price' => 'Consulte', 'badge' => 'Novo', 'badge_type' => 'new', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'briefcase', 'title' => 'TechSoup Brasil', 'description' => 'Processo de registro e validação para filantropia digital.', 'price' => 'R$ 197,00', 'badge' => '', 'badge_type' => '', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'briefcase', 'title' => 'Microsoft, Canva e Slack para ONGs', 'description' => 'Liberação de contas premium e produtividade para sua equipe.', 'price' => 'R$ 147,00', 'badge' => 'Novo', 'badge_type' => 'new', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'diagnostic', 'title' => 'Diagnóstico Organizacional', 'description' => 'Análise completa da operação com recomendações práticas.', 'price' => 'R$ 497,00', 'badge' => '', 'badge_type' => '', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'hand', 'title' => 'Implantação Acompanhada', 'description' => 'Implementação do painel com acompanhamento personalizado.', 'price' => 'Em breve', 'badge' => '', 'badge_type' => '', 'cta' => 'Saber mais', 'url' => url('/contato')],
            ['icon' => 'calendar', 'title' => 'Workshop: Gestão Eficiente para Igrejas', 'description' => 'Workshop ao vivo com boas práticas de gestão e operação.', 'price' => 'R$ 97,00', 'badge' => 'Novo', 'badge_type' => 'new', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'globe', 'title' => 'Site para Igrejas', 'description' => 'Sites profissionais para publicação com identidade visual da organização.', 'price' => 'R$ 67,00/mês', 'badge' => '', 'badge_type' => '', 'cta' => 'Ver detalhes', 'url' => url('/hub/sites')],
        ];

        try {
            $items = [];

            $products = Product::allWithCategory();
            foreach ($products as $product) {
                $status = (string) ($product['status'] ?? 'active');
                if ($status === 'inactive') {
                    continue;
                }

                $price = isset($product['price']) ? (float) $product['price'] : 0.0;
                $items[] = [
                    'icon' => 'gift',
                    'title' => (string) ($product['name'] ?? 'Produto'),
                    'description' => (string) ($product['description'] ?? 'Soluções para fortalecer sua operação.'),
                    'price' => $status === 'coming_soon' ? 'Em breve' : $this->formatMoney($price),
                    'badge' => !empty($product['is_featured']) ? 'Destaque' : '',
                    'badge_type' => !empty($product['is_featured']) ? 'hot' : '',
                    'cta' => $status === 'coming_soon' ? 'Saber mais' : 'Ver detalhes',
                    'url' => url('/contato'),
                ];
                $items[count($items) - 1] = $this->normalizeShowcaseCard($items[count($items) - 1]);
            }

            $services = Service::all('sort_order');
            foreach ($services as $service) {
                if ((string) ($service['status'] ?? 'active') !== 'active') {
                    continue;
                }

                $price = isset($service['price']) ? (float) $service['price'] : 0.0;
                $recurrence = (string) ($service['recurrence'] ?? 'one_time');
                $priceLabel = $this->formatMoney($price);
                if ($recurrence === 'monthly') {
                    $priceLabel .= '/mes';
                }

                $items[] = [
                    'icon' => 'briefcase',
                    'title' => (string) ($service['name'] ?? 'Serviço'),
                    'description' => (string) ($service['description'] ?? 'Serviço especializado para sua organização.'),
                    'price' => $priceLabel,
                    'badge' => '',
                    'badge_type' => '',
                    'cta' => 'Ver detalhes',
                    'url' => url('/contato'),
                ];
                $items[count($items) - 1] = $this->normalizeShowcaseCard($items[count($items) - 1]);
            }

            if (!empty($items)) {
                $items = $this->ensureMandatoryShowcaseItems($items, $fallback);
                return array_slice($items, 0, 12);
            }
        } catch (\Throwable $e) {
            // fallback below
        }

        return $fallback;
    }

    private function ensureMandatoryShowcaseItems(array $items, array $fallback): array
    {
        $titles = array_map(static fn(array $item): string => strtolower((string) ($item['title'] ?? '')), $items);
        $required = [
            'expositor ia',
            'gestão de tráfego pago',
            'site para igrejas',
        ];

        foreach ($required as $needle) {
            $found = false;
            foreach ($titles as $title) {
                if (str_contains($title, $needle)) {
                    $found = true;
                    break;
                }
            }

            if ($found) {
                continue;
            }

            foreach ($fallback as $fallbackItem) {
                $fallbackTitle = strtolower((string) ($fallbackItem['title'] ?? ''));
                if (str_contains($fallbackTitle, $needle)) {
                    $items[] = $fallbackItem;
                    break;
                }
            }
        }

        return $items;
    }

    private function normalizeShowcaseCard(array $item): array
    {
        $title = strtolower((string) ($item['title'] ?? ''));

        if (str_contains($title, 'expositor')) {
            $item['icon'] = 'book';
            $item['title'] = 'Expositor IA (por créditos)';
            $item['description'] = 'Geração de esboços e estudos com IA mediante consumo de créditos por uso.';
            $item['price'] = 'Por créditos';
            $item['cta'] = 'Acessar';
            $item['url'] = url('/hub/expositor-ia');
        }

        if (str_contains($title, 'tráfego') || str_contains($title, 'trafego') || str_contains($title, 'ad grants')) {
            $item['icon'] = 'megaphone';
        }

        if (str_contains($title, 'sites prontos para igrejas') || str_contains($title, 'sites para igrejas')) {
            $item['title'] = 'Site para igrejas';
        }

        return $item;
    }

    private function translateSubscriptionStatus(string $status): string
    {
        return match ($status) {
            'active' => 'Ativa',
            'trial' => 'Período de teste',
            'past_due' => 'Pagamento pendente',
            'cancelled' => 'Cancelada',
            'expired' => 'Expirada',
            default => 'Inativa',
        };
    }

    private function formatMoney(float $value): string
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }
}
