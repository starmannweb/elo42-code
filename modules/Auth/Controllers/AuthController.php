<?php

declare(strict_types=1);

namespace Modules\Auth\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Services\AuthService;
use App\Models\User;
use App\Models\Organization;

class AuthController extends Controller
{
    private AuthService $auth;

    public function __construct()
    {
        parent::__construct();
        $this->auth = new AuthService();
    }

    public function showLogin(Request $request): void
    {
        if (Session::isAuthenticated()) {
            redirect('/hub');
        }

        $this->view('auth/login', [
            'pageTitle' => 'Entrar — Elo 42',
        ]);
    }

    public function login(Request $request): void
    {
        $data = $this->validate($request, [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->auth->attempt(
            $request->input('email'),
            $request->input('password')
        );

        if (!$result['success']) {
            Session::flash('error', $result['error']);
            Session::setOld(['email' => $request->input('email')]);
            redirect('/login');
        }

        $intended = Session::getFlash('intended_url', '/hub');
        redirect($intended);
    }

    public function showRegister(Request $request): void
    {
        if (Session::isAuthenticated()) {
            redirect('/hub');
        }

        $this->view('auth/register', [
            'pageTitle' => 'Criar conta — Elo 42',
        ]);
    }

    public function register(Request $request): void
    {
        $data = $this->validate($request, [
            'first_name'            => 'required|min:2|max:100',
            'last_name'             => 'required|min:2|max:100',
            'email'                 => 'required|email',
            'phone'                 => 'required',
            'password'              => 'required|min:8',
            'password_confirmation' => 'required',
            'terms'                 => 'required',
        ]);

        if ($request->input('password') !== $request->input('password_confirmation')) {
            Session::flash('error', 'As senhas não coincidem.');
            Session::setOld($request->only(['first_name', 'last_name', 'email', 'phone']));
            redirect('/cadastro');
        }

        $result = $this->auth->register($request->all());

        if (!$result['success']) {
            Session::flash('error', $result['error']);
            Session::setOld($request->only(['first_name', 'last_name', 'email', 'phone']));
            redirect('/cadastro');
        }

        Session::flash('success', 'Conta criada com sucesso! Complete o cadastro da sua organização.');
        redirect('/onboarding/organizacao');
    }

    public function logout(Request $request): void
    {
        $this->auth->logout();
        redirect('/');
    }

    public function showForgotPassword(Request $request): void
    {
        $this->view('auth/forgot-password', [
            'pageTitle' => 'Recuperar senha — Elo 42',
        ]);
    }

    public function forgotPassword(Request $request): void
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $result = $this->auth->createPasswordReset($request->input('email'));

        Session::flash('success', $result['message']);
        redirect('/esqueci-senha');
    }

    public function showResetPassword(Request $request): void
    {
        $token = $request->param('token');

        if (!$token) {
            Session::flash('error', 'Token de recuperação inválido.');
            redirect('/esqueci-senha');
        }

        $this->view('auth/reset-password', [
            'pageTitle' => 'Redefinir senha — Elo 42',
            'token'     => $token,
        ]);
    }

    public function resetPassword(Request $request): void
    {
        $this->validate($request, [
            'password'              => 'required|min:8',
            'password_confirmation' => 'required',
        ]);

        $token = $request->input('token');

        if ($request->input('password') !== $request->input('password_confirmation')) {
            Session::flash('error', 'As senhas não coincidem.');
            redirect('/redefinir-senha/' . $token);
        }

        $result = $this->auth->resetPassword($token, $request->input('password'));

        if (!$result['success']) {
            Session::flash('error', $result['error']);
            redirect('/esqueci-senha');
        }

        Session::flash('success', $result['message']);
        redirect('/login');
    }

    public function verifyEmail(Request $request): void
    {
        $token = $request->param('token');
        $result = $this->auth->verifyEmail($token);

        if (!$result['success']) {
            Session::flash('error', $result['error']);
        } else {
            Session::flash('success', $result['message']);
        }

        if (Session::isAuthenticated()) {
            redirect('/hub');
        }

        redirect('/login');
    }

    public function showOnboardingOrganization(Request $request): void
    {
        if (!Session::isAuthenticated()) {
            redirect('/login');
        }

        $user = Session::user();
        if (User::hasOrganization((int) $user['id'])) {
            redirect('/hub');
        }

        $this->view('auth/onboarding-organization', [
            'pageTitle' => 'Cadastrar organização — Elo 42',
        ]);
    }

    public function storeOrganization(Request $request): void
    {
        if (!Session::isAuthenticated()) {
            redirect('/login');
        }

        $this->validate($request, [
            'org_name'  => 'required|min:3|max:255',
            'org_type'  => 'required',
            'org_phone' => 'required',
            'org_city'  => 'required',
            'org_state' => 'required',
        ]);

        $user = Session::user();

        try {
            Organization::createWithOwner([
                'name'     => $request->input('org_name'),
                'type'     => $request->input('org_type'),
                'document' => $request->input('org_document') ?: null,
                'email'    => $request->input('org_email') ?: $user['email'],
                'phone'    => $request->input('org_phone'),
                'city'     => $request->input('org_city'),
                'state'    => $request->input('org_state'),
                'website'  => $request->input('org_website') ?: null,
                'settings' => json_encode([
                    'legal_name'   => $request->input('org_legal_name') ?: null,
                    'members_count' => $request->input('org_members_count') ?: null,
                ]),
            ], (int) $user['id']);

            // Reload session with organization data
            $org = User::getOrganization((int) $user['id']);
            $permissions = User::getPermissions((int) $user['id']);

            if ($org) {
                Session::set('organization', [
                    'id'        => $org['id'],
                    'name'      => $org['name'],
                    'slug'      => $org['slug'],
                    'type'      => $org['type'],
                    'plan'      => $org['plan'],
                    'status'    => $org['status'],
                    'role_slug' => $org['role_slug'] ?? null,
                    'role_name' => $org['role_name'] ?? null,
                ]);
            }

            $userData = Session::user();
            $userData['permissions'] = $permissions;
            Session::set('user', $userData);

            Session::flash('success', 'Organização criada com sucesso! Bem-vindo ao Elo 42.');
            redirect('/hub');

        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao criar organização. Tente novamente.');
            Session::setOld($request->all());
            redirect('/onboarding/organizacao');
        }
    }
}
