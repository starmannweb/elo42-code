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
