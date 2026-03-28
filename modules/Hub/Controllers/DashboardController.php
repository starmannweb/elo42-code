<?php

declare(strict_types=1);

namespace Modules\Hub\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request): void
    {
        $user = Session::user();
        $organization = Session::get('organization');

        $hasOrg = is_array($organization) && !empty($organization['id']);
        if (!$hasOrg) {
            try {
                if (User::hasOrganization((int) ($user['id'] ?? 0))) {
                    $organization = User::getOrganization((int) ($user['id'] ?? 0));
                }
            } catch (\Throwable $e) {
                Session::flash('warning', 'A plataforma esta em modo de contingencia. Alguns modulos podem ficar indisponiveis temporariamente.');
            }
        }

        $firstName = explode(' ', (string) ($user['name'] ?? 'Usuario'))[0] ?? 'Usuario';

        $greeting = match (true) {
            (int) date('H') < 12  => 'Bom dia',
            (int) date('H') < 18  => 'Boa tarde',
            default               => 'Boa noite',
        };

        $this->view('hub/dashboard', [
            'pageTitle'     => 'Dashboard - Elo 42',
            'user'          => $user,
            'organization'  => $organization,
            'firstName'     => $firstName,
            'greeting'      => $greeting,
            'showcaseItems' => $this->buildShowcaseItems(),
        ]);
    }

    private function buildShowcaseItems(): array
    {
        $fallback = [
            ['icon' => 'book', 'title' => 'Expositor IA (ilimitado)', 'description' => 'Geracoes ilimitadas de sermoes e exegese teologica.', 'price' => 'R$ 37,00/mes', 'badge' => 'Novo', 'badge_type' => 'new', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'monitor', 'title' => 'Painel de Gestao de Igrejas', 'description' => 'Sistema completo de gestao para membros, eventos e financeiro.', 'price' => 'R$ 97,00/mes', 'badge' => 'Mais vendido', 'badge_type' => 'hot', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'gift', 'title' => 'Google para ONGs', 'description' => 'Trilha guiada para aprovacao e criacao do Google Workspace gratuito.', 'price' => 'R$ 297,00', 'badge' => 'Novo', 'badge_type' => 'new', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'gift', 'title' => 'Google Ad Grants', 'description' => 'Implantacao e aprovacao do edital para receber ate US$ 10.000/mes.', 'price' => 'R$ 497,00', 'badge' => '', 'badge_type' => '', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'briefcase', 'title' => 'TechSoup Brasil', 'description' => 'Processo de registro e validacao para filantropia digital.', 'price' => 'R$ 197,00', 'badge' => '', 'badge_type' => '', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'briefcase', 'title' => 'Microsoft, Canva e Slack para ONGs', 'description' => 'Liberacao de contas premium e produtividade para sua equipe.', 'price' => 'R$ 147,00', 'badge' => 'Novo', 'badge_type' => 'new', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'diagnostic', 'title' => 'Diagnostico Organizacional', 'description' => 'Analise completa da operacao com recomendacoes praticas.', 'price' => 'R$ 497,00', 'badge' => '', 'badge_type' => '', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'hand', 'title' => 'Implantacao Acompanhada', 'description' => 'Implementacao do painel com acompanhamento personalizado.', 'price' => 'Em breve', 'badge' => '', 'badge_type' => '', 'cta' => 'Saber mais', 'url' => url('/contato')],
            ['icon' => 'calendar', 'title' => 'Workshop: Gestao Eficiente para Igrejas', 'description' => 'Workshop ao vivo com boas praticas de gestao e operacao.', 'price' => 'R$ 97,00', 'badge' => 'Novo', 'badge_type' => 'new', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
            ['icon' => 'globe', 'title' => 'Sites Prontos para Igrejas', 'description' => 'Templates profissionais para lancamento rapido da sua presenca digital.', 'price' => 'R$ 67,00/mes', 'badge' => 'Em breve', 'badge_type' => 'coming', 'cta' => 'Ver detalhes', 'url' => url('/contato')],
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
                    'description' => (string) ($product['description'] ?? 'Solucoes para fortalecer sua operacao.'),
                    'price' => $status === 'coming_soon' ? 'Em breve' : $this->formatMoney($price),
                    'badge' => !empty($product['is_featured']) ? 'Destaque' : '',
                    'badge_type' => !empty($product['is_featured']) ? 'hot' : '',
                    'cta' => $status === 'coming_soon' ? 'Saber mais' : 'Ver detalhes',
                    'url' => url('/contato'),
                ];
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
                    'title' => (string) ($service['name'] ?? 'Servico'),
                    'description' => (string) ($service['description'] ?? 'Servico especializado para sua organizacao.'),
                    'price' => $priceLabel,
                    'badge' => '',
                    'badge_type' => '',
                    'cta' => 'Ver detalhes',
                    'url' => url('/contato'),
                ];
            }

            if (!empty($items)) {
                return array_slice($items, 0, 12);
            }
        } catch (\Throwable $e) {
            // fallback below
        }

        return $fallback;
    }

    private function formatMoney(float $value): string
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }
}
