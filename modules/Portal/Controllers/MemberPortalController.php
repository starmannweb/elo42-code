<?php

declare(strict_types=1);

namespace Modules\Portal\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;

class MemberPortalController extends Controller
{
    private function buildBaseContext(string $breadcrumb, string $activeMenu): array
    {
        $user = Session::user() ?? [];
        $organization = Session::get('organization') ?? [];

        $firstName = explode(' ', (string) ($user['name'] ?? 'Usuário'))[0] ?? 'Usuário';
        $greeting = match (true) {
            (int) date('H') < 12  => 'Bom dia',
            (int) date('H') < 18  => 'Boa tarde',
            default               => 'Boa noite',
        };

        return [
            'user'         => $user,
            'organization' => $organization,
            'firstName'    => $firstName,
            'greeting'     => $greeting,
            'breadcrumb'   => $breadcrumb,
            'activeMenu'   => $activeMenu,
        ];
    }

    public function demoAccess(Request $request): void
    {
        // Força criação temporária na sessão para simular membro (Apenas demonstração)
        Session::set('user', [
            'id' => 9999,
            'name' => 'Membro de Demonstração',
            'email' => 'membro@demo.elo42.com',
            'phone' => '(11) 98765-4321',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        Session::set('organization', [
            'id' => 9999,
            'name' => 'Igreja Alpha Demonstração',
            'slug' => 'igreja-alpha-demonstracao',
            'plan' => 'premium',
            'role_name' => 'Membro'
        ]);

        Session::flash('success', 'Acesso Demo do Membro criado com sucesso!');
        redirect('/membro');
    }

    public function index(Request $request): void
    {
        try {
            $context = $this->buildBaseContext('Início', 'inicio');
            
            $this->view('portal/dashboard', array_merge($context, [
                'pageTitle' => 'Início — Portal do Membro',
            ]));
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function bible(Request $request): void
    {
        try {
            $context = $this->buildBaseContext('Bíblia', 'biblia');
            
            $this->view('portal/bible', array_merge($context, [
                'pageTitle' => 'Bíblia Sagrada — Portal do Membro',
            ]));
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function readingPlans(Request $request): void
    {
        try {
            $context = $this->buildBaseContext('Planos de Leitura', 'planos-leitura');
            
            $this->view('portal/reading-plans', array_merge($context, [
                'pageTitle' => 'Planos de Leitura — Portal do Membro',
            ]));
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function ministrations(Request $request): void
    {
        try {
            $context = $this->buildBaseContext('Ministrações', 'ministracoes');
            
            $this->view('portal/ministrations', array_merge($context, [
                'pageTitle' => 'Ministrações — Portal do Membro',
            ]));
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function courses(Request $request): void
    {
        try {
            $context = $this->buildBaseContext('Cursos', 'cursos');
            
            $this->view('portal/courses', array_merge($context, [
                'pageTitle' => 'Cursos — Portal do Membro',
            ]));
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function events(Request $request): void
    {
        try {
            $context = $this->buildBaseContext('Eventos', 'eventos');
            
            $this->view('portal/events', array_merge($context, [
                'pageTitle' => 'Eventos — Portal do Membro',
            ]));
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function requests(Request $request): void
    {
        try {
            $context = $this->buildBaseContext('Pedidos', 'pedidos');
            
            $this->view('portal/requests', array_merge($context, [
                'pageTitle' => 'Central de Solicitações — Portal do Membro',
            ]));
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function offerings(Request $request): void
    {
        try {
            $context = $this->buildBaseContext('Ofertas', 'ofertas');
            
            $this->view('portal/offerings', array_merge($context, [
                'pageTitle' => 'Dízimos & Ofertas — Portal do Membro',
            ]));
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function settings(Request $request): void
    {
        try {
            $context = $this->buildBaseContext('Configurações', 'configuracoes');
            
            $this->view('portal/settings', array_merge($context, [
                'pageTitle' => 'Configurações — Portal do Membro',
            ]));
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    protected function handleError(\Throwable $e): void
    {
        Session::flash('error', 'Ocorreu um erro ao carregar a página: ' . $e->getMessage());
        redirect('/membro');
    }
}
