<?php

declare(strict_types=1);

namespace Modules\Site\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class SiteController extends Controller
{
    public function manifest(Request $request): void
    {
        $organization = Session::get('organization');
        $organization = is_array($organization) ? $organization : [];
        $settings = $this->manifestSettings((int) ($organization['id'] ?? 0));
        $fallbackName = (string) ($organization['name'] ?? 'Elo 42');
        $appName = trim((string) ($settings['pwa_name'] ?? '')) ?: $fallbackName;
        $shortName = trim((string) ($settings['pwa_short_name'] ?? '')) ?: 'Elo 42';
        $description = trim((string) ($settings['pwa_desc'] ?? '')) ?: 'Aplicativo oficial da igreja com agenda, conteúdos, ofertas e área de membros.';
        $themeColor = trim((string) ($settings['theme_color'] ?? '')) ?: (trim((string) ($settings['appearance_primary'] ?? '')) ?: '#1e3a8a');
        $backgroundColor = trim((string) ($settings['background_color'] ?? '')) ?: (trim((string) ($settings['appearance_background'] ?? '')) ?: '#ffffff');
        $icon192 = trim((string) ($settings['pwa_icon_192'] ?? '')) ?: '/assets/img/logo-color-new.png';
        $icon512 = trim((string) ($settings['pwa_icon_512'] ?? '')) ?: $icon192;

        header('Content-Type: application/manifest+json; charset=UTF-8');
        header('Cache-Control: no-store, no-cache, must-revalidate');

        echo json_encode([
            'id' => '/membro',
            'name' => $appName,
            'short_name' => $shortName,
            'description' => $description,
            'start_url' => '/membro',
            'scope' => '/',
            'display' => 'standalone',
            'orientation' => 'portrait-primary',
            'background_color' => $backgroundColor,
            'theme_color' => $themeColor,
            'lang' => 'pt-BR',
            'icons' => [
                ['src' => $icon192, 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any'],
                ['src' => $icon512, 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any maskable'],
            ],
            'categories' => ['productivity', 'lifestyle'],
            'shortcuts' => [
                ['name' => 'Área de membros', 'short_name' => 'Membros', 'url' => '/membro', 'icons' => [['src' => $icon192, 'sizes' => '96x96']]],
                ['name' => 'Gestão da igreja', 'short_name' => 'Gestão', 'url' => '/gestao', 'icons' => [['src' => $icon192, 'sizes' => '96x96']]],
                ['name' => 'Hub Elo 42', 'short_name' => 'Hub', 'url' => '/hub', 'icons' => [['src' => $icon192, 'sizes' => '96x96']]],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    private function manifestSettings(int $organizationId): array
    {
        if ($organizationId <= 0) {
            return [];
        }

        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("SELECT `key`, value FROM settings WHERE organization_id = :organization_id");
            $stmt->execute(['organization_id' => $organizationId]);

            $settings = [];
            foreach ($stmt->fetchAll() as $row) {
                $settings[(string) $row['key']] = (string) ($row['value'] ?? '');
            }

            return $settings;
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function generatedSite(Request $request): void
    {
        $slug = trim((string) $request->param('slug', ''));
        if ($slug === '') {
            http_response_code(404);
            echo '<h1>Site não encontrado</h1>';
            return;
        }

        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("
                SELECT os.*, o.name AS organization_name, o.type AS organization_type
                FROM organization_sites os
                LEFT JOIN organizations o ON o.id = os.organization_id
                WHERE os.slug = :slug
                LIMIT 1
            ");
            $stmt->execute(['slug' => $slug]);
            $site = $stmt->fetch();
        } catch (\Throwable $e) {
            $site = null;
        }

        if (!is_array($site) || empty($site)) {
            $site = $this->fallbackGeneratedSite($slug);
        }

        $organizationId = (int) ($site['organization_id'] ?? 0);

        $this->view('site/generated', [
            'pageTitle' => (string) ($site['site_title'] ?? $site['organization_name'] ?? 'Site institucional') . ' — Elo 42',
            'metaDescription' => (string) ($site['site_description'] ?? 'Site institucional gerado pela Elo 42.'),
            'site' => $site,
            'events' => $this->generatedSiteEvents($organizationId),
            'campaigns' => $this->generatedSiteCampaigns($organizationId),
        ]);
    }

    private function fallbackGeneratedSite(string $slug): array
    {
        $organization = $this->findOrganizationByGeneratedSlug($slug);
        $name = trim((string) ($organization['name'] ?? ''));
        if ($name === '') {
            $name = ucwords(str_replace('-', ' ', $slug));
        }

        return [
            'organization_id' => (int) ($organization['id'] ?? 0),
            'organization_name' => $name,
            'organization_type' => (string) ($organization['type'] ?? 'church'),
            'site_title' => $name,
            'site_description' => 'Site institucional em preparação. Em breve esta página reunirá agenda, informações, campanhas e canais de contato.',
            'about_text' => 'Este site foi reservado pela organização e está pronto para receber identidade visual, conteúdo, eventos e informações públicas.',
            'template' => 'Em construção',
            'theme_color' => '#0A4DFF',
            'cta_label' => 'Voltar ao Elo 42',
            'cta_url' => url('/'),
        ];
    }

    private function findOrganizationByGeneratedSlug(string $slug): ?array
    {
        try {
            $rows = Database::connection()
                ->query('SELECT id, name, type FROM organizations ORDER BY id DESC LIMIT 200')
                ->fetchAll();

            foreach ($rows as $row) {
                if ($this->publicSlug((string) ($row['name'] ?? '')) === $slug) {
                    return $row;
                }
            }
        } catch (\Throwable $e) {
            return null;
        }

        return null;
    }

    private function publicSlug(string $value): string
    {
        $normalized = function_exists('iconv') ? iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) : $value;
        $normalized = is_string($normalized) ? $normalized : $value;
        $slug = strtolower((string) preg_replace('/[^a-zA-Z0-9]+/', '-', $normalized));
        return trim($slug, '-') ?: 'site-institucional';
    }

    private function generatedSiteEvents(int $organizationId): array
    {
        if ($organizationId <= 0 || !$this->siteTableExists('events')) {
            return [];
        }

        try {
            $stmt = Database::connection()->prepare("
                SELECT title, description, location, start_date
                FROM events
                WHERE organization_id = :organization_id AND status IN ('published', 'ongoing', 'draft')
                ORDER BY start_date ASC
                LIMIT 6
            ");
            $stmt->execute(['organization_id' => $organizationId]);
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function generatedSiteCampaigns(int $organizationId): array
    {
        if ($organizationId <= 0 || !$this->siteTableExists('campaigns')) {
            return [];
        }

        try {
            $stmt = Database::connection()->prepare("
                SELECT title, description, goal_amount, raised_amount, designation
                FROM campaigns
                WHERE organization_id = :organization_id AND status IN ('active', 'published', 'completed')
                ORDER BY created_at DESC
                LIMIT 4
            ");
            $stmt->execute(['organization_id' => $organizationId]);
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function siteTableExists(string $table): bool
    {
        try {
            $pdo = Database::connection();
            $driver = (string) $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);

            if ($driver === 'sqlite') {
                $stmt = $pdo->prepare("SELECT name FROM sqlite_master WHERE type = 'table' AND name = :table LIMIT 1");
                $stmt->execute(['table' => $table]);
                return (bool) $stmt->fetchColumn();
            }

            $stmt = $pdo->prepare('SHOW TABLES LIKE :table');
            $stmt->execute(['table' => $table]);
            return (bool) $stmt->fetchColumn();
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function home(Request $request): void
    {
        $this->view('site/home', [
            'pageTitle' => 'Elo 42 — Gestão, tecnologia e impacto para a sua missão',
            'metaDescription' => 'A Elo 42 reúne implantação, benefícios, suporte e gestão em uma plataforma feita para igrejas e organizações.',
        ]);
    }

    public function about(Request $request): void
    {
        $this->view('site/about', [
            'pageTitle' => 'Sobre — Elo 42',
            'metaDescription' => 'Conheça a Elo 42, uma plataforma que centraliza gestão, implantação, benefícios e suporte para igrejas e organizações.',
        ]);
    }

    public function solutions(Request $request): void
    {
        $this->view('site/solutions', [
            'pageTitle' => 'Soluções — Elo 42',
            'metaDescription' => 'Descubra as soluções da Elo 42 para igrejas e organizações: gestão, implantação, consultoria e mais.',
        ]);
    }

    public function platform(Request $request): void
    {
        $this->view('site/platform', [
            'pageTitle' => 'Plataforma de Gestão — Elo 42',
            'metaDescription' => 'Conheça a plataforma de gestão Elo 42: membros, finanças, eventos, comunicação e relatórios em um só lugar.',
        ]);
    }

    public function benefits(Request $request): void
    {
        $this->view('site/benefits', [
            'pageTitle' => 'Benefícios — Elo 42',
            'metaDescription' => 'Veja os benefícios que a Elo 42 oferece para igrejas e organizações parceiras.',
        ]);
    }

    public function consulting(Request $request): void
    {
        $this->view('site/consulting', [
            'pageTitle' => 'Consultoria — Elo 42',
            'metaDescription' => 'Consultoria especializada para igrejas e organizações que buscam ordem, estrutura e crescimento sustentável.',
        ]);
    }

    public function features(Request $request): void
    {
        $this->view('site/features', [
            'pageTitle' => 'Funcionalidades — Elo 42',
            'metaDescription' => 'Explore todas as funcionalidades da plataforma Elo 42 para gestão de igrejas e organizações.',
        ]);
    }

    public function faq(Request $request): void
    {
        $this->view('site/faq', [
            'pageTitle' => 'Perguntas Frequentes — Elo 42',
            'metaDescription' => 'Tire suas dúvidas sobre a plataforma Elo 42 e como ela pode ajudar sua organização.',
        ]);
    }

    public function contact(Request $request): void
    {
        $this->view('site/contact', [
            'pageTitle' => 'Contato — Elo 42',
            'metaDescription' => 'Entre em contato com a equipe Elo 42. Estamos prontos para ajudar sua organização.',
        ]);
    }

    public function service(Request $request): void
    {
        $slug = $request->param('slug');

        $services = [
            'central-elo42' => [
                'title' => 'Central Elo 42',
                'subtitle' => 'Checklist, prazos e controle da implantação.',
                'description' => 'Trilhas completas com checklist, documentos e passo a passo para ativar Google para ONGs, Ad Grants, TechSoup e outros programas. A Central Elo 42 é o hub onde sua organização acompanha cada etapa da implantação com clareza e controle.',
                'features' => [
                    'Checklists personalizados por etapa',
                    'Controle de prazos e marcos',
                    'Documentação centralizada',
                    'Acompanhamento de progresso em tempo real',
                    'Integração com Google para ONGs e TechSoup',
                    'Suporte dedicado durante todo o processo',
                ],
                'meta' => 'Central Elo 42 — Checklist, prazos e controle da implantação para igrejas e organizações.',
            ],
            'plataforma-gestao' => [
                'title' => 'Plataforma de Gestão',
                'subtitle' => 'Tudo que sua organização precisa em um só lugar.',
                'description' => 'Cadastre membros, acompanhe eventos e organize entradas e saídas com relatórios simples e objetivos. A Plataforma de Gestão Elo 42 centraliza toda a operação da sua organização.',
                'features' => [
                    'Cadastro e gestão de membros',
                    'Controle financeiro completo',
                    'Agenda de eventos integrada',
                    'Comunicação interna',
                    'Relatórios e dashboards',
                    'Controle de permissões por perfil',
                ],
                'meta' => 'Plataforma de Gestão Elo 42 — Membros, finanças, eventos e relatórios em um só lugar.',
            ],
            'google-ad-grants' => [
                'title' => 'Google para Nonprofits',
                'subtitle' => 'Google Ad Grants, Workspace, YouTube e mais para sua organização.',
                'description' => 'Acesse o programa Google para Organizações sem fins lucrativos e desbloqueie até US$ 10.000/mês em anúncios com o Google Ad Grants, Google Workspace gratuito, YouTube Nonprofits e ferramentas do Google Earth. Configuramos, ativamos e gerenciamos tudo para manter sua conta em conformidade e evitar bloqueios.',
                'features' => [
                    'Google Ad Grants — até US$ 10.000/mês em anúncios',
                    'Google Workspace gratuito para toda a equipe',
                    'YouTube Nonprofits com recursos exclusivos',
                    'Configuração e ativação completa da conta',
                    'Gestão contínua para manter conformidade',
                    'Suporte para evitar suspensões e bloqueios',
                ],
                'meta' => 'Google para Nonprofits — Ad Grants, Workspace, YouTube e mais para sua organização.',
            ],
            'expositor-ia' => [
                'title' => 'Expositor IA',
                'subtitle' => 'Plataforma completa de aprofundamento exegético e produção ministerial com IA para pastores.',
                'description' => 'O Expositor IA apoia pastores no caminho do texto bíblico até o púlpito: exegese, revisão pastoral, sermões, séries, EBD, discipulado, pequenos grupos, formação de liderança, preparo para casais e planejamento anual da igreja.',
                'features' => [
                    'Caminho Exegético — contexto histórico-literário, palavras-chave em grego/hebraico e eixo cristológico',
                    'Sermão em Etapas — construção estruturada com refinamento assistido por IA',
                    'Discipulado — programa de 12 encontros com Guia do Discipulador e Folha do Discípulo',
                    'Aconselhamento Pastoral — sessões estruturadas com perfil detectado e referência confessional',
                    'Estudo Bíblico / PG — materiais completos com perguntas progressivas e desafios semanais',
                    'EBD (Escola Bíblica) — lições com exposição bíblica, conexão redentiva e aplicações',
                    'Formação de Líderes — 7 encontros com doutrina essencial e orientações ao pastor-formador',
                    'Preparação de Casais — guia pastoral + material do casal com exercícios semanais',
                    'Planejamento Anual — diagnóstico pastoral, pilares ministeriais e indicadores espirituais',
                ],
                'meta' => 'Expositor IA - Plataforma de aprofundamento exegético e produção ministerial com IA para pastores.',
            ],
            'consultoria' => [
                'title' => 'Consultoria',
                'subtitle' => 'Diagnóstico, revisão e plano de ação personalizado.',
                'description' => 'Para quem quer acelerar: diagnóstico completo, revisão de processos, implantação acompanhada e plano de ação personalizado. Nossa consultoria ajuda organizações a operarem com mais estrutura e eficiência.',
                'features' => [
                    'Diagnóstico organizacional completo',
                    'Revisão de processos internos',
                    'Plano de ação personalizado',
                    'Implantação acompanhada',
                    'Mentoria para lideranças',
                    'Acompanhamento contínuo pós-implantação',
                ],
                'meta' => 'Consultoria Elo 42 — Diagnóstico, implantação e plano de ação para sua organização.',
                'badge' => 'Em breve',
            ],
            'sites-prontos' => [
                'title' => 'Site para Igrejas',
                'subtitle' => 'Presença digital profissional em poucos cliques.',
                'description' => 'Modelos profissionais de página de ministérios + integrações (WhatsApp, eventos, doações/PIX, SEO básico). Publique rápido, com padrão Elo 42.',
                'features' => [
                    'Modelos profissionais prontos para uso',
                    'Integração com WhatsApp e redes sociais',
                    'Sistema de doações e PIX integrado',
                    'Agenda de eventos publicável',
                    'SEO básico configurado',
                    'Padrão visual Elo 42',
                ],
                'meta' => 'Site para Igrejas Elo 42 — Modelos profissionais para igrejas e ministérios.',
                'badge' => 'Em breve',
            ],
        ];

        if (!isset($services[$slug])) {
            http_response_code(404);
            echo '<h1>Serviço não encontrado</h1>';
            return;
        }

        $service = $services[$slug];

        $this->view('site/service', [
            'pageTitle' => $service['title'] . ' — Elo 42',
            'metaDescription' => $service['meta'],
            'service' => $service,
            'slug' => $slug,
        ]);
    }

    public function contactSubmit(Request $request): void
    {
        $data = $this->validate($request, [
            'name'    => 'required|min:3|max:255',
            'email'   => 'required|email',
            'phone'   => 'required',
            'subject' => 'required',
            'message' => 'required|min:10',
        ]);

        // TODO: Process contact form (email, save to DB, etc.)

        \App\Core\Session::flash('success', 'Mensagem enviada com sucesso! Retornaremos em breve.');
        redirect('/contato');
    }
}
