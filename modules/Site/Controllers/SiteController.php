<?php

declare(strict_types=1);

namespace Modules\Site\Controllers;

use App\Core\Controller;
use App\Core\Request;

class SiteController extends Controller
{
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
            'hokmah-expositor' => [
                'title' => 'Hokmah Expositor',
                'subtitle' => 'Plataforma completa de aprofundamento exegético e produção ministerial com IA para pastores reformados.',
                'description' => 'O Hokmah Expositor é uma plataforma completa de aprofundamento exegético e produção ministerial criada exclusivamente para pastores reformados. Vai muito além de um gerador de sermões: combina rigor exegético histórico-gramatical, identidade confessional reformada e fluxo ministerial integrado — do texto bíblico ao púlpito, do discipulado ao planejamento anual da igreja.',
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
                'meta' => 'Hokmah Expositor — Plataforma de aprofundamento exegético e produção ministerial com IA para pastores reformados.',
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
                'title' => 'Sites Prontos',
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
                'meta' => 'Sites Prontos Elo 42 — Modelos profissionais de sites para igrejas e ministérios.',
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
