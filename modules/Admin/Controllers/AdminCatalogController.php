<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Database;
use App\Models\Product;
use App\Models\Service;
use App\Models\Benefit;
use App\Models\Subscription;
use App\Models\Ticket;
use App\Models\PlatformSetting;

class AdminCatalogController extends Controller
{
    // ---- Products ----
    public function products(Request $req): void
    {
        Session::flash('warning', 'Produtos foi removido do Admin. Use Serviços para gerenciar o catálogo da plataforma.');
        redirect('/admin/servicos');
    }

    public function createProduct(Request $req): void
    {
        redirect('/admin/servicos/novo');
    }

    public function storeProduct(Request $req): void
    {
        redirect('/admin/servicos');
    }

    public function editProduct(Request $req): void
    {
        redirect('/admin/servicos');
    }

    public function updateProduct(Request $req): void
    {
        redirect('/admin/servicos');
    }

    public function storeProductCategory(Request $req): void
    {
        redirect('/admin/servicos');
    }

    // ---- Services ----
    public function services(Request $req): void
    {
        $services = [];
        $degraded = false;

        try {
            $this->ensureDefaultServices();
            $services = Service::all('sort_order');
            if (empty($services)) {
                $services = $this->defaultServices(true);
            }
        } catch (\Throwable $e) {
            $degraded = true;
            $services = $this->defaultServices(true);
            error_log('[ADMIN_SERVICES] ' . $e->getMessage());
        }

        $this->view('admin/services/index', [
            'pageTitle' => 'Serviços — Admin', 'breadcrumb' => 'Serviços',
            'services' => $services,
            'degraded' => $degraded,
        ]);
    }

    public function createService(Request $req): void
    {
        $this->view('admin/services/form', [
            'pageTitle' => 'Novo serviço', 'breadcrumb' => 'Serviços / Novo', 'item' => null,
        ]);
    }

    public function storeService(Request $req): void
    {
        $this->validate($req, ['name' => 'required', 'slug' => 'required']);
        Service::create($req->only(['name','slug','description','rules','price','recurrence','status','sort_order']));
        Session::flash('success', 'Serviço criado.');
        redirect('/admin/servicos');
    }

    public function editService(Request $req): void
    {
        $item = Service::find((int) $req->param('id'));
        if (!$item) { redirect('/admin/servicos'); }
        $this->view('admin/services/form', [
            'pageTitle' => 'Editar — ' . e($item['name']), 'breadcrumb' => 'Serviços / Editar', 'item' => $item,
        ]);
    }

    public function updateService(Request $req): void
    {
        $id = (int) $req->param('id');
        $this->validate($req, ['name' => 'required']);
        Service::update($id, $req->only(['name','slug','description','rules','price','recurrence','status','sort_order']));
        Session::flash('success', 'Serviço atualizado.');
        redirect('/admin/servicos');
    }

    // ---- Benefits ----
    public function benefits(Request $req): void
    {
        $benefits = [];
        $services = [];
        $users = [];
        $degraded = false;

        try {
            $this->ensureDefaultServices();
            $benefits = Benefit::allWithUsageCount();
            $services = Service::all('sort_order');
            $users = $this->listUsers();
        } catch (\Throwable $e) {
            $degraded = true;
            error_log('[ADMIN_BENEFITS] ' . $e->getMessage());
        }

        $this->view('admin/benefits/index', [
            'pageTitle' => 'Cortesias — Admin', 'breadcrumb' => 'Cortesias',
            'benefits' => $benefits,
            'services' => $services,
            'users' => $users,
            'degraded' => $degraded,
        ]);
    }

    public function createBenefit(Request $req): void
    {
        try { $this->ensureDefaultServices(); } catch (\Throwable $e) { error_log('[ADMIN_BENEFITS_FORM] ' . $e->getMessage()); }

        $this->view('admin/benefits/form', [
            'pageTitle' => 'Nova cortesia', 'breadcrumb' => 'Cortesias / Nova', 'item' => null,
            'services' => Service::all('sort_order'),
            'organizations' => $this->listOrganizations(),
            'users' => $this->listUsers(),
        ]);
    }

    public function storeBenefit(Request $req): void
    {
        $this->validate($req, ['name' => 'required', 'slug' => 'required']);
        Benefit::create($this->benefitPayload($req));
        Session::flash('success', 'Cortesia criada.');
        redirect('/admin/cortesias');
    }

    public function editBenefit(Request $req): void
    {
        $item = Benefit::find((int) $req->param('id'));
        if (!$item) { redirect('/admin/cortesias'); }
        $this->ensureDefaultServices();

        $this->view('admin/benefits/form', [
            'services'      => Service::all('sort_order'),
            'organizations' => $this->listOrganizations(),
            'users'         => $this->listUsers(),
            'pageTitle'     => 'Editar — ' . e($item['name']), 'breadcrumb' => 'Cortesias / Editar', 'item' => $item,
        ]);
    }

    public function updateBenefit(Request $req): void
    {
        $id = (int) $req->param('id');
        $this->validate($req, ['name' => 'required']);
        Benefit::update($id, $this->benefitPayload($req));
        Session::flash('success', 'Cortesia atualizada.');
        redirect('/admin/cortesias');
    }

    // ---- Subscriptions ----
    public function subscriptions(Request $req): void
    {
        $filters = ['status' => $req->input('status', '')];
        $subscriptions = [];
        $degraded = false;

        try {
            $subscriptions = Subscription::allWithOrg($filters);
        } catch (\Throwable $e) {
            $degraded = true;
            error_log('[ADMIN_SUBS] ' . $e->getMessage());
        }

        $this->view('admin/subscriptions/index', [
            'pageTitle'     => 'Assinaturas — Admin', 'breadcrumb' => 'Assinaturas',
            'subscriptions' => $subscriptions, 'filters' => $filters,
            'degraded'      => $degraded,
        ]);
    }

    public function showSubscription(Request $req): void
    {
        $sub = Subscription::find((int) $req->param('id'));
        if (!$sub) { redirect('/admin/assinaturas'); }
        $pdo = Database::connection();
        $org = $pdo->prepare("SELECT * FROM organizations WHERE id = :id"); $org->execute(['id' => $sub['organization_id']]);
        $this->view('admin/subscriptions/show', [
            'pageTitle' => 'Assinatura #' . $sub['id'], 'breadcrumb' => 'Assinaturas / Detalhe',
            'sub' => $sub, 'org' => $org->fetch(), 'history' => Subscription::getHistory((int)$sub['id']),
        ]);
    }

    public function updateSubscription(Request $req): void
    {
        $id = (int) $req->param('id');
        $sub = Subscription::find($id);
        if (!$sub) { redirect('/admin/assinaturas'); }
        $data = $req->only(['plan_name','plan_slug','price','billing_cycle','status','trial_ends_at','starts_at','expires_at']);
        Subscription::update($id, $data);

        // Log history
        $pdo = Database::connection();
        $pdo->prepare("INSERT INTO subscription_history (subscription_id, action, old_plan, new_plan, created_by) VALUES (:sid, :act, :old, :new, :uid)")
            ->execute(['sid' => $id, 'act' => 'upgraded', 'old' => $sub['plan_name'], 'new' => $data['plan_name'] ?? $sub['plan_name'], 'uid' => Session::user()['id']]);
        Session::flash('success', 'Assinatura atualizada.');
        redirect('/admin/assinaturas/' . $id);
    }

    // ---- Tickets ----
    public function tickets(Request $req): void
    {
        $filters = ['status' => $req->input('status', ''), 'priority' => $req->input('priority', '')];
        $tickets = [];
        $degraded = false;

        try {
            $tickets = Ticket::allAdmin($filters);
        } catch (\Throwable $e) {
            $degraded = true;
            error_log('[ADMIN_TICKETS] ' . $e->getMessage());
        }

        $this->view('admin/tickets/index', [
            'pageTitle' => 'Tickets — Admin', 'breadcrumb' => 'Tickets',
            'tickets' => $tickets, 'filters' => $filters,
            'degraded' => $degraded,
        ]);
    }

    public function showTicket(Request $req): void
    {
        $ticket = Ticket::find((int) $req->param('id'));
        if (!$ticket) { redirect('/admin/tickets'); }
        $this->view('admin/tickets/show', [
            'pageTitle' => 'Ticket #' . $ticket['id'], 'breadcrumb' => 'Tickets / #' . $ticket['id'],
            'ticket' => $ticket, 'replies' => Ticket::getReplies((int)$ticket['id']),
        ]);
    }

    public function replyTicket(Request $req): void
    {
        $id = (int) $req->param('id');
        $this->validate($req, ['message' => 'required']);
        $pdo = Database::connection();
        $pdo->prepare("INSERT INTO ticket_replies (ticket_id, user_id, message, is_admin) VALUES (:tid, :uid, :msg, 1)")
            ->execute(['tid' => $id, 'uid' => Session::user()['id'], 'msg' => $req->input('message')]);
        Session::flash('success', 'Resposta enviada.');
        redirect('/admin/tickets/' . $id);
    }

    public function updateTicketStatus(Request $req): void
    {
        $id = (int) $req->param('id');
        $data = ['status' => $req->input('status')];
        if ($req->input('status') === 'resolved') { $data['resolved_at'] = date('Y-m-d H:i:s'); }
        if ($req->input('status') === 'closed') { $data['closed_at'] = date('Y-m-d H:i:s'); }
        Ticket::update($id, $data);
        Session::flash('success', 'Status atualizado.');
        redirect('/admin/tickets/' . $id);
    }

    // ---- Reports ----
    public function reports(Request $req): void
    {
        $startDate = $req->input('start_date', date('Y-m-01'));
        $endDate = $req->input('end_date', date('Y-m-t'));
        $totalUsers = 0; $totalOrgs = 0; $newUsers = 0; $newOrgs = 0;
        $activeSubs = 0; $openTickets = 0;
        $degraded = false;

        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE created_at >= :s AND created_at <= :e");
            $stmt->execute(['s' => $startDate, 'e' => $endDate . ' 23:59:59']);
            $newUsers = (int) $stmt->fetchColumn();

            $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM organizations WHERE created_at >= :s AND created_at <= :e");
            $stmt2->execute(['s' => $startDate, 'e' => $endDate . ' 23:59:59']);
            $newOrgs = (int) $stmt2->fetchColumn();

            $totalUsers = (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
            $totalOrgs = (int) $pdo->query("SELECT COUNT(*) FROM organizations")->fetchColumn();
            $activeSubs = Subscription::countByStatus('active');
            $openTickets = Ticket::countOpen();
        } catch (\Throwable $e) {
            $degraded = true;
            error_log('[ADMIN_REPORTS] ' . $e->getMessage());
        }

        $this->view('admin/reports/index', [
            'pageTitle' => 'Relatórios — Admin', 'breadcrumb' => 'Relatórios',
            'totalUsers' => $totalUsers,
            'totalOrgs' => $totalOrgs,
            'newUsers' => $newUsers, 'newOrgs' => $newOrgs,
            'activeSubs' => $activeSubs,
            'openTickets' => $openTickets,
            'filters' => ['start_date' => $startDate, 'end_date' => $endDate],
            'degraded' => $degraded,
        ]);
    }

    // ---- Logs ----
    public function logs(Request $req): void
    {
        $search = $req->input('search', '');
        $module = $req->input('module', '');
        $logs = [];
        $degraded = false;

        try {
            $pdo = Database::connection();
            $where = '1=1'; $params = [];
            if ($search) { $where .= " AND (u.name LIKE :s OR al.action LIKE :s)"; $params['s'] = "%{$search}%"; }
            if ($module) { $where .= " AND al.module = :m"; $params['m'] = $module; }

            $stmt = $pdo->prepare("SELECT al.*, u.name as user_name, u.email as user_email FROM audit_logs al LEFT JOIN users u ON al.user_id = u.id WHERE {$where} ORDER BY al.created_at DESC LIMIT 100");
            $stmt->execute($params);
            $logs = $stmt->fetchAll();
        } catch (\Throwable $e) {
            $degraded = true;
            error_log('[ADMIN_LOGS] ' . $e->getMessage());
        }

        $this->view('admin/logs/index', [
            'pageTitle' => 'Logs — Admin', 'breadcrumb' => 'Logs',
            'logs' => $logs,
            'filters' => ['search' => $search, 'module' => $module],
            'degraded' => $degraded,
        ]);
    }

    // ---- Settings ----
    public function settings(Request $req): void
    {
        $settings = [];
        $degraded = false;

        try {
            $this->ensureDefaultPlatformSettings();
            $settings = PlatformSetting::byGroup();
            if (empty($settings)) {
                $settings = $this->defaultPlatformSettings();
            }
        } catch (\Throwable $e) {
            $degraded = true;
            $settings = $this->defaultPlatformSettings();
            error_log('[ADMIN_SETTINGS] ' . $e->getMessage());
        }

        $this->view('admin/settings/index', [
            'pageTitle' => 'Configurações — Admin', 'breadcrumb' => 'Configurações',
            'settings' => $settings,
            'degraded' => $degraded,
        ]);
    }

    public function updateSettings(Request $req): void
    {
        try {
            $this->ensureDefaultPlatformSettings();
        } catch (\Throwable $e) {
            error_log('[ADMIN_SETTINGS_DEFAULTS] ' . $e->getMessage());
        }

        $settings = $req->input('settings', []);
        if (is_array($settings)) {
            foreach ($settings as $key => $value) {
                if (in_array((string) $key, ['openai_api_key', 'pagou_api_key', 'pagou_webhook_secret', 'resend_api_key', 'evolution_api_key', 'evolution_webhook_secret', 'platform_webhook_secret'], true) && trim((string) $value) === '') {
                    continue;
                }
                PlatformSetting::set($key, $value, (int) Session::user()['id']);
            }
        }
        Session::flash('success', 'Configurações atualizadas.');
        redirect('/admin/configuracoes');
    }

    private function benefitPayload(Request $req): array
    {
        $data = $req->only(['name','slug','description','requirements','status','max_usage','valid_until','service_id','duration_days','target_type','target_id','target_label']);

        foreach (['max_usage', 'service_id', 'duration_days', 'target_id'] as $field) {
            if (($data[$field] ?? '') === '') {
                $data[$field] = null;
            }
        }

        if (!in_array((string) ($data['target_type'] ?? ''), ['organization', 'user'], true)) {
            $data['target_type'] = null;
        }

        if (($data['valid_until'] ?? '') === '') {
            $data['valid_until'] = null;
        }

        if (($data['target_type'] ?? null) === 'user' && !empty($data['target_id']) && trim((string) ($data['target_label'] ?? '')) === '') {
            foreach ($this->listUsers() as $user) {
                if ((int) ($user['id'] ?? 0) === (int) $data['target_id']) {
                    $data['target_label'] = (string) ($user['name'] ?? $user['email'] ?? '');
                    break;
                }
            }
        }

        return $data;
    }

    private function listOrganizations(): array
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->query("SELECT id, name FROM organizations ORDER BY name ASC");
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            error_log('[ADMIN_BENEFITS_ORGS] ' . $e->getMessage());
            return [];
        }
    }

    private function listUsers(): array
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->query("SELECT id, name, email FROM users ORDER BY name ASC, email ASC LIMIT 500");
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            error_log('[ADMIN_BENEFITS_USERS] ' . $e->getMessage());
            return [];
        }
    }

    private function ensureDefaultServices(): void
    {
        $defaults = $this->defaultServices();

        foreach ($defaults as $index => $service) {
            $service['sort_order'] = ($index + 1) * 10;
            $existing = Service::first('slug', $service['slug']);
            if ($existing) {
                $updates = [];
                foreach (['name', 'description', 'rules', 'sort_order'] as $field) {
                    if ((string) ($existing[$field] ?? '') !== (string) ($service[$field] ?? '')) {
                        $updates[$field] = $service[$field];
                    }
                }
                if (!empty($updates)) {
                    Service::update((int) $existing['id'], $updates);
                }
                continue;
            }

            Service::create($service);
        }
    }

    private function defaultServices(bool $withIds = false): array
    {
        $defaults = [
            ['name' => 'Painel de Gestão para Igrejas', 'slug' => 'painel-gestao-igrejas', 'description' => 'Sistema completo para membros, finanças, ministérios, eventos, relatórios e rotina pastoral. Inclui até 100 usuários da plataforma de gestão.', 'rules' => 'Acesso por assinatura da igreja responsável. Acima de 100 usuários pode haver custo adicional.', 'price' => 67.00, 'recurrence' => 'monthly', 'status' => 'active'],
            ['name' => 'Site para Igrejas', 'slug' => 'site-para-igrejas', 'description' => 'Construtor de site institucional com modelos, dados cadastrais, preview e publicação para assinantes.', 'rules' => 'Plano avulso de site por R$ 67,00/mês. No combo com gestão, o total fica R$ 99,90/mês.', 'price' => 67.00, 'recurrence' => 'monthly', 'status' => 'active'],
            ['name' => 'Central Pastoral IA', 'slug' => 'central-pastoral-ia', 'description' => 'Criação assistida de sermões, estudos bíblicos, séries, ministrações e planos de leitura.', 'rules' => 'Materiais publicados aparecem no sistema de gestão e na área do membro.', 'price' => 0, 'recurrence' => 'monthly', 'status' => 'active'],
            ['name' => 'Google Ad Grants', 'slug' => 'google-ad-grants', 'description' => 'Apoio para elegibilidade, configuração e gestão de campanhas para ONGs e igrejas.', 'rules' => 'Disponibilidade depende das regras do programa e validação da instituição.', 'price' => 0, 'recurrence' => 'monthly', 'status' => 'active'],
            ['name' => 'Google para ONGs', 'slug' => 'google-para-ongs', 'description' => 'Orientação para ativar ferramentas Google Workspace e recursos para organizações elegíveis.', 'rules' => 'Sujeito a aprovação externa do programa.', 'price' => 0, 'recurrence' => 'one_time', 'status' => 'active'],
            ['name' => 'Gestão de Tráfego Pago', 'slug' => 'gestao-trafego-pago', 'description' => 'Planejamento, criação e acompanhamento de campanhas pagas para comunicação e captação.', 'rules' => 'Investimento de mídia não incluso no serviço.', 'price' => 0, 'recurrence' => 'monthly', 'status' => 'active'],
            ['name' => 'TechSoup Brasil', 'slug' => 'techsoup-brasil', 'description' => 'Apoio para identificar benefícios, licenças e oportunidades de tecnologia para organizações.', 'rules' => 'Sujeito às regras e disponibilidade dos parceiros.', 'price' => 0, 'recurrence' => 'one_time', 'status' => 'active'],
            ['name' => 'Microsoft, Canva e Slack', 'slug' => 'microsoft-canva-slack', 'description' => 'Apoio na estruturação de ferramentas colaborativas, design e produtividade para equipes.', 'rules' => 'Benefícios dependem da elegibilidade da instituição.', 'price' => 0, 'recurrence' => 'one_time', 'status' => 'active'],
            ['name' => 'Implantação Acompanhada', 'slug' => 'implantacao-acompanhada', 'description' => 'Acompanhamento para configurar dados iniciais, equipe, módulos e rotina de adoção.', 'rules' => 'Agenda conforme disponibilidade operacional.', 'price' => 0, 'recurrence' => 'one_time', 'status' => 'active'],
            ['name' => 'Diagnóstico Organizacional', 'slug' => 'diagnostico-organizacional', 'description' => 'Mapeamento de processos, comunicação, governança e oportunidades de melhoria.', 'rules' => 'Pode exigir reunião de levantamento com responsáveis.', 'price' => 0, 'recurrence' => 'one_time', 'status' => 'active'],
            ['name' => 'Workshop de Capacitação', 'slug' => 'workshop-capacitacao', 'description' => 'Treinamentos para liderança, comunicação, tecnologia e uso da plataforma.', 'rules' => 'Formato e duração definidos por demanda.', 'price' => 0, 'recurrence' => 'one_time', 'status' => 'active'],
        ];

        foreach ($defaults as $index => &$service) {
            $service['sort_order'] = ($index + 1) * 10;
            if ($withIds) {
                $service['id'] = -($index + 1);
            }
        }
        unset($service);

        return $defaults;
    }

    private function ensureDefaultPlatformSettings(): void
    {
        $defaults = [
            ['openai_api_key', '', 'ai', 'Chave da OpenAI usada pela Central Pastoral IA'],
            ['openai_model', 'gpt-4o-mini', 'ai', 'Modelo principal da Central Pastoral IA'],
            ['openai_temperature', '0.6', 'ai', 'Temperatura de geração da Central Pastoral IA'],
            ['openai_timeout', '60', 'ai', 'Tempo limite das chamadas OpenAI em segundos'],
            ['plan_management_monthly_price', '67.00', 'billing', 'Plano de gestão mensal até 100 usuários'],
            ['plan_site_monthly_price', '67.00', 'billing', 'Plano avulso do site'],
            ['plan_combo_monthly_price', '99.90', 'billing', 'Combo gestão + site'],
            ['management_included_users', '100', 'billing', 'Usuários incluídos no plano de gestão'],
            ['pagou_environment', 'sandbox', 'payments', 'Ambiente da integração Pagou: sandbox ou production'],
            ['pagou_api_key', '', 'payments', 'Chave da API Pagou usada para cobrança dos assinantes do Hub'],
            ['pagou_webhook_secret', '', 'payments', 'Segredo do webhook Pagou para validar eventos de pagamento'],
            ['pagou_webhook_url', '/webhooks/pagou', 'payments', 'Endpoint que receberá eventos da Pagou'],
            ['pagou_default_gateway', 'pagou', 'payments', 'Gateway oficial para cobrança recorrente dos assinantes do Hub'],
            ['resend_api_key', '', 'email', 'Chave da API Resend usada para disparos transacionais da plataforma'],
            ['resend_from_email', 'suporte@elo42.com.br', 'email', 'Email remetente padrao usado nos disparos'],
            ['resend_from_name', 'Elo 42', 'email', 'Nome do remetente padrao usado nos disparos'],
            ['resend_base_url', 'https://api.resend.com', 'email', 'URL base da API Resend'],
            ['evolution_base_url', '', 'whatsapp', 'URL base da Evolution API'],
            ['evolution_api_key', '', 'whatsapp', 'Chave global de autenticacao da Evolution API'],
            ['evolution_instance', '', 'whatsapp', 'Instancia padrao da Evolution API usada para disparos'],
            ['evolution_webhook_url', '/webhooks/evolution', 'whatsapp', 'Endpoint local para receber eventos da Evolution API'],
            ['evolution_webhook_secret', '', 'whatsapp', 'Segredo para validar eventos recebidos da Evolution API'],
            ['platform_webhook_base_url', '', 'webhooks', 'URL publica base para callbacks e automacoes externas'],
            ['platform_webhook_secret', '', 'webhooks', 'Segredo compartilhado para validar webhooks gerais da plataforma'],
            ['lead_capture_webhook_url', '/webhooks/leads', 'webhooks', 'Endpoint para captura externa de leads e formularios'],
            ['billing_webhook_url', '/webhooks/pagou', 'webhooks', 'Endpoint para eventos financeiros e cobrancas'],
            ['email_events_webhook_url', '/webhooks/resend', 'webhooks', 'Endpoint para eventos de entrega, abertura e falha de emails'],
            ['ia_free_generations_monthly', '3', 'ai', 'Geracoes gratuitas mensais por workspace'],
            ['ia_credit_cost', '1', 'ai', 'Creditos consumidos por geracao da Central Pastoral IA'],
            ['site_publish_cname_target', 'sites.elo42.com.br', 'sites', 'Destino CNAME recomendado para www ou subdominios'],
            ['site_publish_apex_ip', '185.158.133.1', 'sites', 'IP usado quando o provedor exigir registro A no dominio raiz'],
            ['site_domain_verify_prefix', '_elo42-verify', 'sites', 'Prefixo do TXT opcional para validar posse do dominio'],
        ];

        $pdo = Database::connection();
        $stmt = $pdo->prepare("
            INSERT INTO platform_settings (setting_key, setting_value, setting_group, description)
            VALUES (:key, :value, :group_name, :description)
        ");

        foreach ($defaults as [$key, $value, $group, $description]) {
            if (PlatformSetting::first('setting_key', $key)) {
                continue;
            }

            $stmt->execute([
                'key' => $key,
                'value' => $value,
                'group_name' => $group,
                'description' => $description,
            ]);
        }
    }
    private function defaultPlatformSettings(): array
    {
        return [
            ['setting_key' => 'openai_api_key', 'setting_value' => '', 'setting_group' => 'ai', 'description' => 'Chave da OpenAI usada pela Central Pastoral IA. Deixe em branco para manter a chave atual.'],
            ['setting_key' => 'openai_model', 'setting_value' => 'gpt-4o-mini', 'setting_group' => 'ai', 'description' => 'Modelo principal usado pela Central Pastoral IA.'],
            ['setting_key' => 'openai_temperature', 'setting_value' => '0.6', 'setting_group' => 'ai', 'description' => 'Criatividade das respostas. Valores menores deixam o material mais conservador.'],
            ['setting_key' => 'openai_timeout', 'setting_value' => '60', 'setting_group' => 'ai', 'description' => 'Tempo limite das chamadas OpenAI em segundos.'],
            ['setting_key' => 'ia_free_generations_monthly', 'setting_value' => '3', 'setting_group' => 'ai', 'description' => 'Geracoes gratuitas mensais por workspace.'],
            ['setting_key' => 'ia_credit_cost', 'setting_value' => '1', 'setting_group' => 'ai', 'description' => 'Creditos consumidos por geracao da Central Pastoral IA.'],
            ['setting_key' => 'plan_management_monthly_price', 'setting_value' => '67.00', 'setting_group' => 'billing', 'description' => 'Plano de gestao mensal com ate 100 usuarios da plataforma.'],
            ['setting_key' => 'plan_site_monthly_price', 'setting_value' => '67.00', 'setting_group' => 'billing', 'description' => 'Plano avulso do site para igrejas.'],
            ['setting_key' => 'plan_combo_monthly_price', 'setting_value' => '99.90', 'setting_group' => 'billing', 'description' => 'Combo gestao + site.'],
            ['setting_key' => 'management_included_users', 'setting_value' => '100', 'setting_group' => 'billing', 'description' => 'Usuarios incluidos no plano de gestao. Acima disso, cobrar adicional.'],
            ['setting_key' => 'pagou_environment', 'setting_value' => 'sandbox', 'setting_group' => 'payments', 'description' => 'Ambiente da integracao Pagou: sandbox ou production.'],
            ['setting_key' => 'pagou_api_key', 'setting_value' => '', 'setting_group' => 'payments', 'description' => 'Chave da API Pagou usada para cobranca dos assinantes do Hub.'],
            ['setting_key' => 'pagou_webhook_secret', 'setting_value' => '', 'setting_group' => 'payments', 'description' => 'Segredo do webhook Pagou para validar eventos de pagamento.'],
            ['setting_key' => 'pagou_webhook_url', 'setting_value' => '/webhooks/pagou', 'setting_group' => 'payments', 'description' => 'Endpoint que recebera eventos da Pagou.'],
            ['setting_key' => 'pagou_default_gateway', 'setting_value' => 'pagou', 'setting_group' => 'payments', 'description' => 'Gateway oficial para cobranca recorrente dos assinantes do Hub.'],
            ['setting_key' => 'resend_api_key', 'setting_value' => '', 'setting_group' => 'email', 'description' => 'Chave da API Resend usada para disparos transacionais da plataforma.'],
            ['setting_key' => 'resend_from_email', 'setting_value' => 'suporte@elo42.com.br', 'setting_group' => 'email', 'description' => 'Email remetente padrao usado nos disparos.'],
            ['setting_key' => 'resend_from_name', 'setting_value' => 'Elo 42', 'setting_group' => 'email', 'description' => 'Nome do remetente padrao usado nos disparos.'],
            ['setting_key' => 'resend_base_url', 'setting_value' => 'https://api.resend.com', 'setting_group' => 'email', 'description' => 'URL base da API Resend.'],
            ['setting_key' => 'evolution_base_url', 'setting_value' => '', 'setting_group' => 'whatsapp', 'description' => 'URL base da Evolution API.'],
            ['setting_key' => 'evolution_api_key', 'setting_value' => '', 'setting_group' => 'whatsapp', 'description' => 'Chave global de autenticacao da Evolution API.'],
            ['setting_key' => 'evolution_instance', 'setting_value' => '', 'setting_group' => 'whatsapp', 'description' => 'Instancia padrao da Evolution API usada para disparos.'],
            ['setting_key' => 'evolution_webhook_url', 'setting_value' => '/webhooks/evolution', 'setting_group' => 'whatsapp', 'description' => 'Endpoint local para receber eventos da Evolution API.'],
            ['setting_key' => 'evolution_webhook_secret', 'setting_value' => '', 'setting_group' => 'whatsapp', 'description' => 'Segredo para validar eventos recebidos da Evolution API.'],
            ['setting_key' => 'platform_webhook_base_url', 'setting_value' => '', 'setting_group' => 'webhooks', 'description' => 'URL publica base para callbacks e automacoes externas.'],
            ['setting_key' => 'platform_webhook_secret', 'setting_value' => '', 'setting_group' => 'webhooks', 'description' => 'Segredo compartilhado para validar webhooks gerais da plataforma.'],
            ['setting_key' => 'lead_capture_webhook_url', 'setting_value' => '/webhooks/leads', 'setting_group' => 'webhooks', 'description' => 'Endpoint para captura externa de leads e formularios.'],
            ['setting_key' => 'billing_webhook_url', 'setting_value' => '/webhooks/pagou', 'setting_group' => 'webhooks', 'description' => 'Endpoint para eventos financeiros e cobrancas.'],
            ['setting_key' => 'email_events_webhook_url', 'setting_value' => '/webhooks/resend', 'setting_group' => 'webhooks', 'description' => 'Endpoint para eventos de entrega, abertura e falha de emails.'],
            ['setting_key' => 'site_publish_cname_target', 'setting_value' => 'sites.elo42.com.br', 'setting_group' => 'sites', 'description' => 'Destino CNAME recomendado para www ou subdominios.'],
            ['setting_key' => 'site_publish_apex_ip', 'setting_value' => '185.158.133.1', 'setting_group' => 'sites', 'description' => 'IP usado quando o provedor exigir registro A no dominio raiz.'],
            ['setting_key' => 'site_domain_verify_prefix', 'setting_value' => '_elo42-verify', 'setting_group' => 'sites', 'description' => 'Prefixo do TXT opcional para validar posse do dominio.'],
        ];
    }
}
