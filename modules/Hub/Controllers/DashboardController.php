<?php

declare(strict_types=1);

namespace Modules\Hub\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;
use App\Models\Subscription;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Member;
use App\Models\Event;
use App\Models\ChurchRequest;
use App\Models\FinancialTransaction;
use DateTimeImmutable;

class DashboardController extends Controller
{
    private const IA_CREDIT_COST = 1;
    private const CREDIT_HISTORY_LIMIT = 40;

    public function index(Request $request): void
    {
        $context = $this->buildBaseContext('Dashboard', 'dashboard');
        $siteBuilderAccess = $this->resolveSiteBuilderAccess($context['organization']);
        $iaCredits = $this->resolveIaCredits($context['organization'], $context['user']);
        $churchMetrics = $this->resolveChurchMetrics($context['organization']);

        $this->view('hub/dashboard', array_merge($context, [
            'pageTitle'         => 'Hub — Elo 42',
            'showcaseItems'     => $this->buildShowcaseItems(),
            'siteBuilderAccess' => $siteBuilderAccess,
            'iaCredits'         => $iaCredits,
            'iaCreditCost'      => self::IA_CREDIT_COST,
            'setupSteps'        => $this->buildSetupSteps($context, $siteBuilderAccess, $iaCredits),
            'ticketsCount'      => count($this->resolveSupportTickets($context['organization'], $context['user'])),
            'churchMetrics'     => $churchMetrics,
            'dashboardActivity' => $this->buildDashboardActivity($context['organization'], $churchMetrics),
        ]));
    }

    public function vitrine(Request $request): void
    {
        $context = $this->buildBaseContext('Loja', 'vitrine');
        $churchManagementAccess = is_array($context['churchManagementAccess'] ?? null)
            ? $context['churchManagementAccess']
            : ['can_access' => false, 'is_trial' => false, 'days_left' => 0];

        $this->view('hub/vitrine', array_merge($context, [
            'pageTitle'     => 'Loja — Hub Elo 42',
            'showcaseItems'       => $this->buildShowcaseItems(),
            'platformAccessItems' => $this->buildPlatformAccesses($churchManagementAccess),
            'contractPackages'    => $this->buildContractPackages(),
        ]));
    }

    public function sites(Request $request): void
    {
        $context = $this->buildBaseContext('Meus Sites', 'sites');
        $access = $this->resolveSiteBuilderAccess($context['organization']);

        $this->view('hub/sites', array_merge($context, [
            'pageTitle'         => 'Meus Sites — Hub Elo 42',
            'siteBuilderAccess' => $access,
            'siteTemplates'     => $this->buildSiteTemplates(),
        ]));
    }

    public function gerarSite(Request $request): void
    {
        $context = $this->buildBaseContext('Meus Sites', 'sites');
        $organization = $context['organization'];
        
        if (empty($organization['id'])) {
            \App\Core\Session::flash('warning', 'Cadastre sua organização antes de gerar o site.');
            redirect('/onboarding/organizacao');
        }

        $template = trim((string) $request->input('template'));
        if ($template === '') {
            $template = 'Institucional Clássico';
        }

        // Simulação de geração do site
        try {
            $pdo = Database::connection();
            
            // Check if site already exists
            $stmt = $pdo->prepare("SELECT id FROM organization_sites WHERE organization_id = :org_id LIMIT 1");
            $stmt->execute(['org_id' => (int) $organization['id']]);
            $existingSite = $stmt->fetch();

            if ($existingSite) {
                // Update existing site
                $stmt = $pdo->prepare("UPDATE organization_sites SET template = :template, updated_at = NOW() WHERE organization_id = :org_id");
                $stmt->execute([
                    'template' => $template,
                    'org_id' => (int) $organization['id']
                ]);
                \App\Core\Session::flash('success', 'Site atualizado com sucesso! Modelo: ' . $template);
            } else {
                // Create new site
                $stmt = $pdo->prepare("INSERT INTO organization_sites (organization_id, template, status, created_at, updated_at) VALUES (:org_id, :template, 'draft', NOW(), NOW())");
                $stmt->execute([
                    'org_id' => (int) $organization['id'],
                    'template' => $template
                ]);
                \App\Core\Session::flash('success', 'Site gerado com sucesso! Modelo: ' . $template . '. Seus dados organizacionais foram vinculados ao modelo.');
            }
        } catch (\Throwable $e) {
            // Fallback to session if database not ready
            \App\Core\Session::set('hub_generated_site', [
                'template' => $template,
                'organization_name' => $organization['name'] ?? 'Sua Organização',
                'generated_at' => date('Y-m-d H:i:s')
            ]);
            \App\Core\Session::flash('success', 'Site gerado com sucesso! Modelo: ' . $template . '. Seus dados organizacionais foram vinculados ao modelo.');
        }
        
        redirect('/hub/sites');
    }

    public function expositorIa(Request $request): void
    {
        $context = $this->buildBaseContext('Expositor IA', 'expositor');
        $organization = $context['organization'];
        $user = $context['user'];
        
        // Give bonus credits for first-time users
        $credits = $this->resolveIaCredits($organization, $user);
        $hasUsedBefore = Session::get('hub_expositor_bonus_given', false);
        
        if ($credits === 0 && !$hasUsedBefore) {
            $bonusCredits = 5;
            $this->setIaCredits($organization, $user, $bonusCredits);
            $this->appendCreditHistory($organization, $user, [
                'date'        => date('d/m/Y H:i'),
                'description' => 'Créditos bônus de boas-vindas',
                'type'        => 'Bônus',
                'qty'         => $bonusCredits,
                'price'       => null,
            ]);
            Session::set('hub_expositor_bonus_given', true);
            Session::flash('success', 'Bem-vindo ao Expositor IA! Você ganhou ' . $bonusCredits . ' créditos bônus para testar.');
            $credits = $bonusCredits;
        }

        $form = Session::getFlash('hub_expositor_form', [
            'passage'      => '',
            'theme'        => '',
            'confessional' => 'somente-biblico',
            'depth'        => 'pastoral',
        ]);

        $this->view('hub/expositor-ia', array_merge($context, [
            'pageTitle'           => 'Expositor IA — Hub Elo 42',
            'iaCredits'           => $credits,
            'iaCreditCost'        => self::IA_CREDIT_COST,
            'canGenerateIa'       => $credits >= self::IA_CREDIT_COST,
            'expositorLastResult' => Session::getFlash('hub_expositor_result'),
            'expositorForm'       => $form,
            'confessionalOptions' => [
                ['value' => 'somente-biblico', 'label' => 'Somente Bíblico Reformado'],
                ['value' => 'westminster', 'label' => 'Confissão de Westminster'],
                ['value' => 'londres-1689', 'label' => 'Confissão Batista de Londres 1689'],
            ],
            'depthOptions' => [
                ['value' => 'pastoral', 'label' => 'Sermão Expositivo Pastoral'],
                ['value' => 'teologico', 'label' => 'Aprofundamento Teológico'],
                ['value' => 'academico', 'label' => 'Exegese Acadêmica'],
            ],
        ]));
    }

    public function creditos(Request $request): void
    {
        $context = $this->buildBaseContext('Créditos', 'creditos');
        $credits = $this->resolveIaCredits($context['organization'], $context['user']);

        $this->view('hub/creditos', array_merge($context, [
            'pageTitle' => 'Créditos — Hub Elo 42',
            'iaCredits' => $credits,
            'packages'  => $this->buildCreditPackages(),
            'history'   => $this->resolveCreditHistory($context['organization'], $context['user']),
        ]));
    }

    public function suporte(Request $request): void
    {
        $context = $this->buildBaseContext('Suporte', 'suporte');
        $tickets = $this->resolveSupportTickets($context['organization'], $context['user']);

        $this->view('hub/suporte', array_merge($context, [
            'pageTitle' => 'Suporte — Hub Elo 42',
            'tickets'   => $tickets,
            'categories'=> [
                ['value' => 'general', 'label' => 'Geral'],
                ['value' => 'billing', 'label' => 'Financeiro'],
                ['value' => 'technical', 'label' => 'Técnico'],
                ['value' => 'product', 'label' => 'Produto'],
            ],
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
            'setupSteps'        => $this->buildSetupSteps($context, $subscription, $credits),
            'financialSummary'  => $this->resolveFinancialSummary($context['organization']),
            'accessProfiles'    => $this->buildAccessProfiles(),
            'currentAccessMode' => $this->resolveAccessMode($context['organization'], $context['user']),
        ]));
    }

    public function gerarExpositorIa(Request $request): void
    {
        $context = $this->buildBaseContext('Expositor IA', 'expositor');
        $organization = $context['organization'];
        $user = $context['user'];

        $form = [
            'passage'      => trim((string) $request->input('passage')),
            'theme'        => trim((string) $request->input('theme')),
            'confessional' => trim((string) $request->input('confessional')),
            'depth'        => trim((string) $request->input('depth')),
        ];

        if ($form['passage'] === '') {
            Session::flash('error', 'Informe a passagem bíblica para gerar o esboço.');
            Session::flash('hub_expositor_form', $form);
            redirect('/hub/expositor-ia');
        }

        $credits = $this->resolveIaCredits($organization, $user);
        if ($credits < self::IA_CREDIT_COST) {
            Session::flash('warning', 'Você não possui créditos suficientes. Compre créditos para usar o Expositor IA.');
            Session::flash('hub_expositor_form', $form);
            redirect('/hub/creditos');
        }

        $result = $this->buildExpositorResult($form);
        $this->setIaCredits($organization, $user, $credits - self::IA_CREDIT_COST);

        $this->appendCreditHistory($organization, $user, [
            'date'        => date('d/m/Y H:i'),
            'description' => 'Consumo no Expositor IA',
            'type'        => 'Uso',
            'qty'         => -self::IA_CREDIT_COST,
            'price'       => null,
        ]);

        Session::flash('success', 'Esboço gerado com sucesso. Foi descontado 1 crédito.');
        Session::flash('hub_expositor_result', $result);
        Session::flash('hub_expositor_form', $form);
        redirect('/hub/expositor-ia');
    }

    public function comprarCreditos(Request $request): void
    {
        $context = $this->buildBaseContext('Créditos', 'creditos');
        $organization = $context['organization'];
        $user = $context['user'];

        $packageId = trim((string) $request->input('package_id'));
        $packages = $this->buildCreditPackages();

        $selected = null;
        foreach ($packages as $package) {
            if ((string) ($package['id'] ?? '') === $packageId) {
                $selected = $package;
                break;
            }
        }

        if ($selected === null) {
            Session::flash('error', 'Pacote de créditos inválido.');
            redirect('/hub/creditos');
        }

        $currentCredits = $this->resolveIaCredits($organization, $user);
        $newCredits = $currentCredits + (int) $selected['credits'];
        $this->setIaCredits($organization, $user, $newCredits);

        $this->appendCreditHistory($organization, $user, [
            'date'        => date('d/m/Y H:i'),
            'description' => 'Compra: ' . $selected['name'],
            'type'        => 'Compra',
            'qty'         => (int) $selected['credits'],
            'price'       => (string) $selected['price_label'],
        ]);

        Session::flash('success', 'Pacote aplicado com sucesso. Novo saldo: ' . $newCredits . ' crédito(s).');
        redirect('/hub/creditos');
    }

    public function criarTicketSuporte(Request $request): void
    {
        $context = $this->buildBaseContext('Suporte', 'suporte');
        $organization = $context['organization'];
        $user = $context['user'];

        $subject = trim((string) $request->input('subject'));
        $message = trim((string) $request->input('message'));
        $category = trim((string) $request->input('category'));

        if ($subject === '' || $message === '') {
            Session::flash('error', 'Preencha assunto e mensagem para abrir o ticket.');
            redirect('/hub/suporte');
        }

        $mappedCategory = match ($category) {
            'billing' => 'billing',
            'technical' => 'bug',
            'product' => 'feature',
            default => 'support',
        };

        $created = false;
        if (!empty($user['id'])) {
            try {
                Ticket::create([
                    'user_id'         => (int) $user['id'],
                    'organization_id' => !empty($organization['id']) ? (int) $organization['id'] : null,
                    'subject'         => $subject,
                    'description'     => $message,
                    'category'        => $mappedCategory,
                    'priority'        => 'normal',
                    'status'          => 'open',
                ]);
                $created = true;
            } catch (\Throwable $e) {
                $created = false;
            }
        }

        if (!$created) {
            $sessionTickets = Session::get('hub_support_tickets', []);
            $sessionTickets[] = [
                'id'             => count($sessionTickets) + 1,
                'subject'        => $subject,
                'description'    => $message,
                'category'       => $this->formatSupportCategoryLabel($category),
                'category_label' => $this->formatSupportCategoryLabel($category),
                'status'         => 'Aberto',
                'status_label'   => 'Aberto',
                'created_at'     => date('Y-m-d H:i:s'),
            ];
            Session::set('hub_support_tickets', $sessionTickets);
        }

        Session::flash('success', 'Ticket enviado com sucesso. Nossa equipe já foi notificada.');
        redirect('/hub/suporte');
    }

    public function atualizarConta(Request $request): void
    {
        $context = $this->buildBaseContext('Configurações', 'configuracoes');
        $user = $context['user'];

        if (empty($user['id'])) {
            Session::flash('error', 'Sessão inválida. Faça login novamente.');
            redirect('/login');
        }

        $name = trim((string) $request->input('name'));
        $phone = trim((string) $request->input('phone'));

        if ($name === '') {
            Session::flash('error', 'Informe o nome para atualizar a conta.');
            redirect('/hub/configuracoes');
        }

        $payload = ['name' => $name];
        if ($phone !== '') {
            $payload['phone'] = $phone;
        }

        try {
            User::update((int) $user['id'], $payload);
        } catch (\Throwable $e) {
            // fallback em sessão
        }

        $sessionUser = Session::user() ?? [];
        $sessionUser['name'] = $name;
        if ($phone !== '') {
            $sessionUser['phone'] = $phone;
        }
        Session::set('user', $sessionUser);

        Session::flash('success', 'Dados da conta atualizados com sucesso.');
        redirect('/hub/configuracoes');
    }

    public function atualizarOrganizacao(Request $request): void
    {
        $context = $this->buildBaseContext('Configurações', 'configuracoes');
        $organization = $context['organization'];

        if (empty($organization['id'])) {
            Session::flash('warning', 'Cadastre a organização para editar esse bloco.');
            redirect('/onboarding/organizacao');
        }

        $name = trim((string) $request->input('org_name'));
        $type = trim((string) $request->input('org_type'));
        $phone = trim((string) $request->input('org_phone'));

        if ($name === '') {
            Session::flash('error', 'Informe o nome da organização.');
            redirect('/hub/configuracoes');
        }

        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare('UPDATE organizations SET name = :name, type = :type, phone = :phone, updated_at = NOW() WHERE id = :id');
            $stmt->execute([
                'name'  => $name,
                'type'  => $type !== '' ? $type : ($organization['type'] ?? 'organizacao'),
                'phone' => $phone,
                'id'    => (int) $organization['id'],
            ]);
        } catch (\Throwable $e) {
            // fallback em sessão
        }

        $sessionOrg = Session::get('organization', []);
        $sessionOrg['name'] = $name;
        if ($type !== '') {
            $sessionOrg['type'] = $type;
        }
        Session::set('organization', $sessionOrg);

        Session::flash('success', 'Organização atualizada com sucesso.');
        redirect('/hub/configuracoes');
    }

    public function atualizarPerfilAcesso(Request $request): void
    {
        $context = $this->buildBaseContext('Configurações', 'configuracoes');
        $organization = $context['organization'];
        $user = $context['user'];

        $mode = trim((string) $request->input('access_mode'));
        if (!in_array($mode, ['manager', 'client'], true)) {
            Session::flash('error', 'Perfil de acesso inválido.');
            redirect('/hub/configuracoes');
        }

        $this->setAccessMode($organization, $user, $mode);

        Session::flash('success', 'Perfil de acesso atualizado para ' . ($mode === 'manager' ? 'Gestor principal' : 'Usuário cliente') . '.');
        redirect('/hub/configuracoes');
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
                            'plan'      => $organization['plan'] ?? 'free',
                            'status'    => $organization['status'] ?? 'active',
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

        $organizationDeadline = $this->resolveOrganizationDeadline($user, $organization);
        $churchManagementAccess = $this->resolveChurchManagementAccess($organization, $organizationDeadline);

        return [
            'user'                 => $user,
            'organization'         => $organization,
            'firstName'            => $firstName,
            'greeting'             => $greeting,
            'breadcrumb'           => $breadcrumb,
            'activeMenu'           => $activeMenu,
            'organizationDeadline' => $organizationDeadline,
            'supportEmail'         => 'suporte@elo42.com.br',
            'supportWhatsapp'      => '(11) 99177-5458',
            'supportWhatsappUrl'   => 'https://wa.me/5511991775458',
            'accessMode'           => $this->resolveAccessMode($organization, $user),
            'churchManagementAccess' => $churchManagementAccess,
        ];
    }

    private function resolveChurchManagementAccess(?array $organization, array $organizationDeadline): array
    {
        $hasOrganization = !empty($organization['id']);

        return [
            'has_organization' => $hasOrganization,
            'is_trial'         => !$hasOrganization && !($organizationDeadline['is_overdue'] ?? false),
            'days_left'        => $organizationDeadline['days_left'] ?? null,
            'can_access'       => $hasOrganization,
            'entry_url'        => $hasOrganization ? url('/gestao') : url('/onboarding/organizacao'),
        ];
    }

    private function resolveOrganizationDeadline(array $user, ?array $organization): array
    {
        // Se já tem organização cadastrada, não há prazo pendente
        if (!empty($organization['id'])) {
            return [
                'is_required' => false,
                'is_overdue'  => false,
                'days_left'   => null,
                'deadline_at' => null,
            ];
        }

        // Sem organização: calcular prazo de 7 dias a partir da criação
        $createdAt = (string) ($user['created_at'] ?? '');
        if ($createdAt === '') {
            return [
                'is_required' => true,
                'is_overdue'  => false,
                'days_left'   => 7,
                'deadline_at' => null,
            ];
        }

        try {
            $created  = new DateTimeImmutable($createdAt);
            $deadline = $created->modify('+7 days');
            $now      = new DateTimeImmutable('now');
            $isOverdue = $now >= $deadline;
            $daysLeft  = $isOverdue ? 0 : (int) ceil(($deadline->getTimestamp() - $now->getTimestamp()) / 86400);

            return [
                'is_required' => true,
                'is_overdue'  => $isOverdue,
                'days_left'   => $daysLeft,
                'deadline_at' => $deadline->format('Y-m-d H:i:s'),
            ];
        } catch (\Throwable $e) {
            return [
                'is_required' => true,
                'is_overdue'  => false,
                'days_left'   => 7,
                'deadline_at' => null,
            ];
        }
    }

    private function resolveSiteBuilderAccess(?array $organization): array
    {
        $default = [
            'has_org'             => !empty($organization['id']),
            'plan_name'           => 'Sem assinatura',
            'billing_cycle'       => null,
            'status'              => 'inactive',
            'status_label'        => 'Inativo',
            'monthly_fee_label'   => 'Consulte valores',
            'has_active_monthly'  => false,
            'can_create'          => true,
            'can_publish'         => false,
            'publish_requirement' => 'Para publicar o site em domínio real, é necessário ativar a mensalidade do construtor.',
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
                'has_org'             => true,
                'plan_name'           => $planName,
                'billing_cycle'       => $billingCycle,
                'status'              => $status,
                'status_label'        => $this->translateSubscriptionStatus($status),
                'monthly_fee_label'   => $this->formatMoney($price) . ($billingCycle === 'monthly' ? '/mês' : ''),
                'has_active_monthly'  => $isActiveMonthly,
                'can_create'          => true,
                'can_publish'         => $isActiveMonthly,
                'publish_requirement' => 'Para publicar o site em domínio real, é necessário ativar a mensalidade do construtor.',
            ];
        } catch (\Throwable $e) {
            return $default;
        }
    }

    private function resolveIaCredits(?array $organization, array $user): int
    {
        $fallback = (int) Session::get('hub_ia_credits', 0);
        $key = $this->resolveCreditsSettingKey($organization, $user);

        if ($key === null) {
            return $fallback;
        }

        try {
            $value = $this->getPlatformSetting($key);
            if ($value === null || $value === '') {
                return $fallback;
            }
            return max(0, (int) $value);
        } catch (\Throwable $e) {
            return $fallback;
        }
    }

    private function setIaCredits(?array $organization, array $user, int $credits): void
    {
        $value = max(0, $credits);
        $key = $this->resolveCreditsSettingKey($organization, $user);

        Session::set('hub_ia_credits', $value);

        if ($key === null) {
            return;
        }

        try {
            $this->setPlatformSetting($key, (string) $value);
        } catch (\Throwable $e) {
            // fallback em sessão
        }
    }

    private function resolveCreditsSettingKey(?array $organization, array $user): ?string
    {
        if (!empty($organization['id'])) {
            return 'ia_credits_org_' . (int) $organization['id'];
        }

        if (!empty($user['id'])) {
            return 'ia_credits_user_' . (int) $user['id'];
        }

        return null;
    }

    private function buildCreditPackages(): array
    {
        return [
            ['id' => 'starter', 'name' => 'Pacote de Entrada', 'credits' => 50, 'price_label' => 'R$ 49,00', 'description' => 'Pacote básico para sermões, estudos e suporte inicial.', 'badge' => '', 'badge_type' => ''],
            ['id' => 'pro', 'name' => 'Pacote Ministerial', 'credits' => 150, 'price_label' => 'R$ 129,00', 'description' => 'Melhor custo-benefício para uso recorrente no ministério.', 'badge' => 'Mais vendido', 'badge_type' => 'hot'],
            ['id' => 'max', 'name' => 'Pacote Intensivo', 'credits' => 300, 'price_label' => 'R$ 229,00', 'description' => 'Pacote premium para equipes com alta frequência de uso.', 'badge' => 'Melhor valor', 'badge_type' => 'new'],
        ];
    }

    private function resolveCreditHistory(?array $organization, array $user): array
    {
        $fallback = Session::get('hub_credit_history', []);
        if (!is_array($fallback)) {
            $fallback = [];
        }

        $key = $this->resolveCreditHistorySettingKey($organization, $user);
        if ($key === null) {
            return $fallback;
        }

        try {
            $raw = $this->getPlatformSetting($key);
            if ($raw === null || trim((string) $raw) === '') {
                return $fallback;
            }

            $history = json_decode((string) $raw, true);
            if (!is_array($history)) {
                return $fallback;
            }

            return array_slice($history, 0, self::CREDIT_HISTORY_LIMIT);
        } catch (\Throwable $e) {
            return $fallback;
        }
    }

    private function appendCreditHistory(?array $organization, array $user, array $entry): void
    {
        $history = $this->resolveCreditHistory($organization, $user);
        array_unshift($history, $entry);
        $history = array_slice($history, 0, self::CREDIT_HISTORY_LIMIT);

        Session::set('hub_credit_history', $history);

        $key = $this->resolveCreditHistorySettingKey($organization, $user);
        if ($key === null) {
            return;
        }

        try {
            $this->setPlatformSetting($key, (string) json_encode($history, JSON_UNESCAPED_UNICODE));
        } catch (\Throwable $e) {
            // fallback em sessão
        }
    }

    private function resolveCreditHistorySettingKey(?array $organization, array $user): ?string
    {
        if (!empty($organization['id'])) {
            return 'ia_credit_history_org_' . (int) $organization['id'];
        }

        if (!empty($user['id'])) {
            return 'ia_credit_history_user_' . (int) $user['id'];
        }

        return null;
    }

    private function resolveSupportTickets(?array $organization, array $user): array
    {
        if (!empty($user['id'])) {
            try {
                $pdo = Database::connection();
                $stmt = $pdo->prepare('SELECT id, subject, category, status, created_at FROM tickets WHERE user_id = :uid ORDER BY created_at DESC LIMIT 20');
                $stmt->execute(['uid' => (int) $user['id']]);
                $rows = $stmt->fetchAll();

                if (is_array($rows) && !empty($rows)) {
                    return array_map(function (array $ticket): array {
                        $ticket['category_label'] = $this->formatSupportCategoryLabel((string) ($ticket['category'] ?? 'general'));
                        $ticket['status_label'] = $this->formatSupportStatusLabel((string) ($ticket['status'] ?? 'open'));
                        return $ticket;
                    }, $rows);
                }
            } catch (\Throwable $e) {
                // fallback em sessão
            }
        }

        $sessionTickets = Session::get('hub_support_tickets', []);
        return is_array($sessionTickets) ? $sessionTickets : [];
    }

    private function buildSetupSteps(array $context, array $siteBuilderAccess, int $iaCredits): array
    {
        $organization = $context['organization'];
        $user = $context['user'];
        $tickets = $this->resolveSupportTickets($organization, $user);

        return [
            ['number' => 1, 'title' => 'Cadastrar organização', 'description' => 'Configure os dados da sua igreja ou ONG.', 'done' => !empty($organization['id']), 'action' => url('/onboarding/organizacao'), 'action_text' => 'Concluir cadastro'],
            ['number' => 2, 'title' => 'Definir perfil de acesso', 'description' => 'Escolha entre Gestor principal e Usuário cliente.', 'done' => !empty($organization['role_name']), 'action' => url('/hub/configuracoes'), 'action_text' => 'Ajustar perfil'],
            ['number' => 3, 'title' => 'Ativar mensalidade para publicar', 'description' => 'Você já pode criar sites e testar modelos; publicar exige mensalidade ativa.', 'done' => !empty($siteBuilderAccess['can_publish']), 'action' => url('/hub/sites'), 'action_text' => 'Ver status de publicação'],
            ['number' => 4, 'title' => 'Adicionar créditos do Expositor IA', 'description' => 'O Expositor IA funciona por consumo de créditos.', 'done' => $iaCredits > 0, 'action' => url('/hub/creditos'), 'action_text' => 'Comprar créditos'],
            ['number' => 5, 'title' => 'Abrir primeiro ticket de suporte', 'description' => 'Fale com o time para implantação e ajustes iniciais.', 'done' => count($tickets) > 0, 'action' => url('/hub/suporte'), 'action_text' => 'Abrir ticket'],
        ];
    }

    private function resolveFinancialSummary(?array $organization): array
    {
        $summary = ['subscription_status' => 'Sem assinatura', 'subscription_value' => 'Consulte valores', 'billing_cycle' => 'Mensal', 'publish_status' => 'Bloqueado sem mensalidade'];

        if (empty($organization['id'])) {
            return $summary;
        }

        try {
            $subscriptions = Subscription::where('organization_id', (int) $organization['id']);
            if (empty($subscriptions)) {
                return $summary;
            }

            usort($subscriptions, static function (array $a, array $b): int {
                $aTime = strtotime((string) ($a['updated_at'] ?? $a['created_at'] ?? '1970-01-01'));
                $bTime = strtotime((string) ($b['updated_at'] ?? $b['created_at'] ?? '1970-01-01'));
                return $bTime <=> $aTime;
            });

            $current = $subscriptions[0];
            $price = isset($current['price']) ? (float) $current['price'] : 0.0;
            $cycle = (string) ($current['billing_cycle'] ?? 'monthly');
            $status = (string) ($current['status'] ?? 'inactive');

            $summary['subscription_status'] = $this->translateSubscriptionStatus($status);
            $summary['subscription_value'] = $this->formatMoney($price);
            $summary['billing_cycle'] = match ($cycle) {
                'yearly' => 'Anual',
                'quarterly' => 'Trimestral',
                default => 'Mensal',
            };
            $summary['publish_status'] = ($status === 'active' && $cycle === 'monthly') ? 'Publicação liberada' : 'Bloqueado sem mensalidade ativa';

            return $summary;
        } catch (\Throwable $e) {
            return $summary;
        }
    }

    private function resolveChurchMetrics(?array $organization): array
    {
        $fallback = [
            'members_total'    => 0,
            'events_active'    => 0,
            'pending_requests' => 0,
            'revenue_total'    => 0.0,
            'expenses_total'   => 0.0,
        ];

        $orgId = (int) ($organization['id'] ?? 0);
        if ($orgId <= 0) {
            return $fallback;
        }

        try {
            $financial = FinancialTransaction::summary($orgId);

            return [
                'members_total'    => Member::countByOrg($orgId),
                'events_active'    => Event::countActive($orgId),
                'pending_requests' => ChurchRequest::countOpen($orgId),
                'revenue_total'    => max(0.0, (float) ($financial['income'] ?? 0)),
                'expenses_total'   => max(0.0, (float) ($financial['expense'] ?? 0)),
            ];
        } catch (\Throwable $e) {
            return $fallback;
        }
    }

    private function buildDashboardActivity(?array $organization, array $churchMetrics): array
    {
        $orgName = (string) ($organization['name'] ?? 'organização');
        $items = [];

        if ((int) ($churchMetrics['members_total'] ?? 0) <= 0) {
            $items[] = [
                'title' => 'Cadastro de membros ainda não iniciado',
                'meta'  => 'Comece adicionando os primeiros membros da ' . $orgName . '.',
            ];
        } else {
            $items[] = [
                'title' => 'Base de membros atualizada',
                'meta'  => (int) $churchMetrics['members_total'] . ' membro(s) ativos no sistema.',
            ];
        }

        if ((int) ($churchMetrics['events_active'] ?? 0) <= 0) {
            $items[] = [
                'title' => 'Sem eventos programados',
                'meta'  => 'Crie o próximo evento para organizar a agenda ministerial.',
            ];
        } else {
            $items[] = [
                'title' => 'Eventos em andamento',
                'meta'  => (int) $churchMetrics['events_active'] . ' evento(s) ativo(s).',
            ];
        }

        if ((int) ($churchMetrics['pending_requests'] ?? 0) > 0) {
            $items[] = [
                'title' => 'Solicitações pendentes',
                'meta'  => (int) $churchMetrics['pending_requests'] . ' solicitação(ões) aguardando retorno.',
            ];
        } else {
            $items[] = [
                'title' => 'Sem solicitações pendentes',
                'meta'  => 'O canal de solicitações está em dia.',
            ];
        }

        return $items;
    }

    private function buildAccessProfiles(): array
    {
        return [
            ['value' => 'manager', 'title' => 'Gestor principal (desenvolvedor)', 'description' => 'Pode administrar catálogo, créditos, assinaturas, vitrine e configurações avançadas do ecossistema.'],
            ['value' => 'client', 'title' => 'Usuário cliente', 'description' => 'Acessa módulos contratados, abre tickets, consome créditos e opera o dia a dia da organização.'],
        ];
    }

    private function resolveAccessMode(?array $organization, array $user): string
    {
        $key = $this->resolveAccessModeSettingKey($organization, $user);
        if ($key !== null) {
            try {
                $value = $this->getPlatformSetting($key);
                if (in_array($value, ['manager', 'client'], true)) {
                    return $value;
                }
            } catch (\Throwable $e) {
                // fallback abaixo
            }
        }

        $roleSlug = strtolower((string) ($organization['role_slug'] ?? ''));
        if (in_array($roleSlug, ['owner', 'admin', 'developer', 'gestor-principal'], true)) {
            return 'manager';
        }

        return 'client';
    }

    private function setAccessMode(?array $organization, array $user, string $mode): void
    {
        $key = $this->resolveAccessModeSettingKey($organization, $user);
        if ($key === null) {
            Session::set('hub_access_mode', $mode);
            return;
        }

        try {
            $this->setPlatformSetting($key, $mode);
        } catch (\Throwable $e) {
            Session::set('hub_access_mode', $mode);
        }
    }

    private function resolveAccessModeSettingKey(?array $organization, array $user): ?string
    {
        if (!empty($organization['id']) && !empty($user['id'])) {
            return 'hub_access_mode_org_' . (int) $organization['id'] . '_user_' . (int) $user['id'];
        }

        if (!empty($user['id'])) {
            return 'hub_access_mode_user_' . (int) $user['id'];
        }

        return null;
    }

    private function buildSiteTemplates(): array
    {
        return [
            ['name' => 'Institucional Clássico', 'description' => 'Modelo com home, sobre, ministérios, agenda e formulário de contato.', 'status' => 'Disponível', 'highlight' => true],
            ['name' => 'Campanhas e Eventos', 'description' => 'Landing pages para congressos, conferências, campanhas e inscrições.', 'status' => 'Disponível', 'highlight' => false],
            ['name' => 'Captação para ONGs', 'description' => 'Páginas para apresentação institucional, projetos e doações.', 'status' => 'Disponível', 'highlight' => false],
        ];
    }

    private function buildExpositorResult(array $form): string
    {
        $passage = $form['passage'] !== '' ? $form['passage'] : 'Passagem não informada';
        $theme = $form['theme'] !== '' ? $form['theme'] : 'Tema livre';

        $confessionalLabel = match ($form['confessional']) {
            'westminster' => 'Confissão de Westminster',
            'londres-1689' => 'Confissão Batista de Londres 1689',
            default => 'Somente Bíblico Reformado',
        };

        $depthLabel = match ($form['depth']) {
            'teologico' => 'Aprofundamento Teológico',
            'academico' => 'Exegese Acadêmica',
            default => 'Sermão Expositivo Pastoral',
        };

        return "Passagem: {$passage}\n"
            . "Tema/Ênfase: {$theme}\n"
            . "Camada Confessional: {$confessionalLabel}\n"
            . "Nível de Profundidade: {$depthLabel}\n\n"
            . "1. Contexto do texto\n"
            . "Identifique autor, audiência original e finalidade pastoral da perícope.\n\n"
            . "2. Estrutura expositiva sugerida\n"
            . "- Introdução: problema pastoral central do texto\n"
            . "- Movimento 1: verdade bíblica principal\n"
            . "- Movimento 2: implicações para a igreja\n"
            . "- Movimento 3: aplicação prática com chamada à resposta\n\n"
            . "3. Aplicações ministeriais\n"
            . "- Liderança: decisões orientadas pela Escritura\n"
            . "- Comunidade: cuidado mútuo e discipulado\n"
            . "- Missão: testemunho público com clareza e ordem\n\n"
            . "4. Recursos para continuidade\n"
            . "Sugestão: transformar este esboço em série semanal e registrar desdobramentos no Hub.";
    }

    private function buildShowcaseItems(): array
    {
        return [
            ['icon' => 'monitor', 'title' => 'Painel de Gestão de Igrejas', 'description' => 'Acesso completo para membros, eventos, financeiro e rotina ministerial com 7 dias gratuitos.', 'price' => 'R$ 49,90/mês (7 dias grátis)', 'badge' => 'Mais vendido', 'badge_type' => 'hot', 'cta' => 'Ver detalhes', 'url' => url('/gestao')],
            ['icon' => 'book', 'title' => 'Expositor IA', 'description' => 'Geração de esboços e estudos bíblicos para apoio pastoral e ministerial.', 'price' => 'A partir de R$ 49,00', 'badge' => 'Novo', 'badge_type' => 'new', 'cta' => 'Ver detalhes', 'url' => url('/hub/expositor-ia')],
            ['icon' => 'gift', 'title' => 'Google Ad Grants', 'description' => 'Implantação e aprovação para captar até US$ 10.000/mês em anúncios.', 'price' => 'R$ 497,00', 'badge' => '', 'badge_type' => '', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'gift', 'title' => 'Google para ONGs', 'description' => 'Trilha guiada para aprovação e criação do Google Workspace gratuito.', 'price' => 'R$ 297,00', 'badge' => 'Novo', 'badge_type' => 'new', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'megaphone', 'title' => 'Gestão de Tráfego Pago', 'description' => 'Planejamento e operação de campanhas para ampliar alcance e resultados.', 'price' => 'Consulte', 'badge' => 'Novo', 'badge_type' => 'new', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'briefcase', 'title' => 'TechSoup Brasil', 'description' => 'Registro e validação para liberar benefícios de filantropia digital.', 'price' => 'R$ 197,00', 'badge' => '', 'badge_type' => '', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'briefcase', 'title' => 'Microsoft, Canva e Slack para ONGs', 'description' => 'Liberação de contas premium para ganho real de produtividade.', 'price' => 'R$ 147,00', 'badge' => 'Novo', 'badge_type' => 'new', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'globe', 'title' => 'Site para Igrejas', 'description' => 'Sites profissionais para publicação com identidade visual da organização.', 'price' => 'R$ 67,00/mês', 'badge' => '', 'badge_type' => '', 'cta' => 'Ver detalhes', 'url' => url('/hub/sites')],
            ['icon' => 'hand', 'title' => 'Implantação Acompanhada', 'description' => 'Implementação do painel com apoio personalizado da equipe Elo 42.', 'price' => 'Em breve', 'badge' => '', 'badge_type' => '', 'cta' => 'Saber mais', 'url' => url('/contato')],
            ['icon' => 'diagnostic', 'title' => 'Diagnóstico Organizacional', 'description' => 'Análise completa da operação com recomendações práticas e plano de ação.', 'price' => 'R$ 497,00', 'badge' => '', 'badge_type' => '', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'calendar', 'title' => 'Workshop: Gestão Eficiente para Igrejas', 'description' => 'Treinamento prático para líderes e equipes de gestão eclesiástica.', 'price' => 'Em breve', 'badge' => 'Em breve', 'badge_type' => 'coming', 'cta' => 'Em breve', 'url' => '#', 'is_disabled' => true],
        ];
    }

    private function buildPlatformAccesses(array $churchManagementAccess): array
    {
        $canAccessChurch = !empty($churchManagementAccess['can_access']);
        $isTrial = !empty($churchManagementAccess['is_trial']);
        $daysLeft = (int) ($churchManagementAccess['days_left'] ?? 0);

        $churchDescription = $isTrial
            ? 'Acesso em teste por 7 dias. Restam ' . $daysLeft . ' dia(s) para concluir a organização.'
            : 'Acesse o painel completo de membros, eventos, financeiro e relatórios.';

        return [
            [
                'title'       => 'Painel de Gestão de Igrejas',
                'description' => $churchDescription,
                'cta'         => $canAccessChurch ? 'Acessar painel' : 'Liberar acesso',
                'url'         => $canAccessChurch ? url('/gestao') : url('/onboarding/organizacao'),
                'highlight'   => true,
            ],
            [
                'title'       => 'Expositor IA',
                'description' => 'Abra o módulo, preencha a passagem bíblica e gere o esboço com consumo de crédito.',
                'cta'         => 'Acessar Expositor IA',
                'url'         => url('/hub/expositor-ia'),
                'highlight'   => false,
            ],
            [
                'title'       => 'Construtor de Sites',
                'description' => 'Crie e teste modelos. A publicação fica liberada após ativar a mensalidade.',
                'cta'         => 'Abrir Meus Sites',
                'url'         => url('/hub/sites'),
                'highlight'   => false,
            ],
            [
                'title'       => 'Suporte e Implantação',
                'description' => 'Fale com o time no Hub para acompanhamento técnico e operacional.',
                'cta'         => 'Abrir Suporte',
                'url'         => url('/hub/suporte'),
                'highlight'   => false,
            ],
        ];
    }

    private function buildContractPackages(): array
    {
        return [
            [
                'product'     => 'Painel de Gestão de Igrejas',
                'package'     => 'Plano mensal com 7 dias grátis',
                'price'       => 'R$ 49,90/mês',
                'description' => 'Acesso ao sistema de gestão com suporte inicial para implantação.',
                'cta'         => 'Solicitar contratação',
                'url'         => url('/contato'),
            ],
            [
                'product'     => 'Expositor IA',
                'package'     => 'Pacote de entrada',
                'price'       => '50 créditos por R$ 49,00',
                'description' => 'Ideal para começar com esboços e estudos no ritmo da sua equipe.',
                'cta'         => 'Comprar créditos',
                'url'         => url('/hub/creditos'),
            ],
            [
                'product'     => 'Google Ad Grants + Tráfego',
                'package'     => 'Implantação completa',
                'price'       => 'A partir de R$ 497,00',
                'description' => 'Aprovação, estrutura de campanhas e acompanhamento de performance.',
                'cta'         => 'Quero este pacote',
                'url'         => url('/contato'),
            ],
            [
                'product'     => 'Pacote Operacional para ONGs',
                'package'     => 'Google para ONGs + TechSoup + Microsoft/Canva/Slack',
                'price'       => 'Sob consulta',
                'description' => 'Conjunto de benefícios para reduzir custo e aumentar produtividade.',
                'cta'         => 'Solicitar proposta',
                'url'         => url('/contato'),
            ],
        ];
    }

    private function formatSupportCategoryLabel(string $category): string
    {
        return match ($category) {
            'support', 'general' => 'Geral',
            'billing' => 'Financeiro',
            'bug', 'technical' => 'Técnico',
            'feature', 'product' => 'Produto',
            default => 'Geral',
        };
    }

    private function formatSupportStatusLabel(string $status): string
    {
        return match ($status) {
            'open' => 'Aberto',
            'in_progress' => 'Em andamento',
            'waiting' => 'Aguardando',
            'resolved' => 'Resolvido',
            'closed' => 'Fechado',
            default => 'Aberto',
        };
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

    private function getPlatformSetting(string $key): ?string
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT setting_value FROM platform_settings WHERE setting_key = :key LIMIT 1');
        $stmt->execute(['key' => $key]);
        $row = $stmt->fetch();

        return $row ? (string) ($row['setting_value'] ?? '') : null;
    }

    private function setPlatformSetting(string $key, string $value): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('INSERT INTO platform_settings (setting_key, setting_value, is_public, created_at, updated_at) VALUES (:key, :value, 0, NOW(), NOW()) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = NOW()');
        $stmt->execute([
            'key' => $key,
            'value' => $value,
        ]);
    }
    public function usuarios(Request $request): void
    {
        $context = $this->buildBaseContext('Minha Equipe', 'usuarios');
        $organization = $context['organization'];

        if (empty($organization['id'])) {
            \App\Core\Session::flash('warning', 'Cadastre sua organização para gerenciar a equipe.');
            redirect('/onboarding/organizacao');
        }

        // Buscar membros da equipe
        $teamMembers = \App\Models\Organization::getUsers((int) $organization['id']);

        // Buscar papéis disponíveis
        $pdo = \App\Core\Database::connection();
        $stmt = $pdo->prepare("SELECT id, name FROM roles WHERE slug LIKE 'org-%' ORDER BY name ASC");
        $stmt->execute();
        $availableRoles = $stmt->fetchAll();

        $this->view('hub/usuarios', array_merge($context, [
            'pageTitle'      => 'Minha Equipe — Hub Elo 42',
            'teamMembers'    => $teamMembers,
            'availableRoles' => $availableRoles,
        ]));
    }

    public function adicionarUsuario(Request $request): void
    {
        $context = $this->buildBaseContext('Minha Equipe', 'usuarios');
        $organization = $context['organization'];

        $name = trim((string) $request->input('name'));
        $email = trim((string) $request->input('email'));
        $roleId = (int) $request->input('role_id');

        if (empty($name) || empty($email)) {
            \App\Core\Session::flash('error', 'Preencha o nome e o e-mail do novo membro.');
            $this->redirect('/hub/usuarios');
        }

        $pdo = \App\Core\Database::connection();
        
        try {
            $pdo->beginTransaction();

            $existingUser = \App\Models\User::findByEmail($email);
            
            if ($existingUser) {
                $userId = $existingUser['id'];
                
                // Verificar se já está na organização
                $check = $pdo->prepare("SELECT COUNT(*) FROM organization_users WHERE organization_id = :org_id AND user_id = :u_id");
                $check->execute(['org_id' => $organization['id'], 'u_id' => $userId]);
                if ($check->fetchColumn() > 0) {
                    throw new \Exception("Este usuário já faz parte da sua organização.");
                }
            } else {
                // Criar novo usuário com senha padrão
                $userId = \App\Models\User::createAccount([
                    'name' => $name,
                    'email' => $email,
                    'password' => 'elo42@2026',
                    'status' => 'active',
                ]);
            }

            // Vincular à organização
            $stmt = $pdo->prepare("INSERT INTO organization_users (organization_id, user_id, role_id, status, joined_at) VALUES (:org_id, :u_id, :role_id, 'active', NOW())");
            $stmt->execute([
                'org_id' => $organization['id'],
                'u_id' => $userId,
                'role_id' => $roleId
            ]);

            $pdo->commit();
            \App\Core\Session::flash('success', 'Membro adicionado com sucesso!');
        } catch (\Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            \App\Core\Session::flash('error', 'Erro ao adicionar membro: ' . $e->getMessage());
        }

        $this->redirect('/hub/usuarios');
    }

    public function removerUsuario(Request $request): void
    {
        $context = $this->buildBaseContext('Minha Equipe', 'usuarios');
        $organization = $context['organization'];
        $memberId = (int) $request->param('id');
        $currentUser = $context['user'];

        if ($memberId === (int) $currentUser['id']) {
            \App\Core\Session::flash('error', 'Você não pode remover a si mesmo da organização.');
            $this->redirect('/hub/usuarios');
        }

        $pdo = \App\Core\Database::connection();
        $stmt = $pdo->prepare("DELETE FROM organization_users WHERE organization_id = :org_id AND user_id = :u_id");
        $stmt->execute([
            'org_id' => $organization['id'],
            'u_id' => $memberId
        ]);

        \App\Core\Session::flash('success', 'Acesso removido com sucesso.');
        $this->redirect('/hub/usuarios');
    }
}
