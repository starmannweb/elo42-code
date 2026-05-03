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
        $siteBuilderAccess = $this->resolveSiteBuilderAccess($context['organization'], $context['user']);
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
        $access = $this->resolveSiteBuilderAccess($context['organization'], $context['user']);
        $organization = is_array($context['organization'] ?? null) ? $context['organization'] : [];
        $currentSite = $this->applyOrganizationSiteDefaults(
            $this->resolveOrganizationSite($context['organization']),
            $organization
        );

        $appearanceSettings = $this->organizationSettings($organization, [
            'appearance_primary',
            'appearance_secondary',
            'appearance_accent',
            'appearance_background',
            'appearance_text',
        ]);

        $this->view('hub/sites', array_merge($context, [
            'pageTitle'          => 'Meus Sites — Hub Elo 42',
            'siteBuilderAccess'  => $access,
            'currentSite'        => $currentSite,
            'siteTemplates'      => $this->buildSiteTemplates(),
            'publishChecklist'   => $this->buildSitePublishChecklist($context['organization'], $currentSite, $access),
            'publishedUrl'       => $this->sitePublicUrl($currentSite),
            'appearanceSettings' => $appearanceSettings,
        ]));
    }

    public function previewSite(Request $request): void
    {
        $context = $this->buildBaseContext('Preview do Site', 'sites');
        $organization = is_array($context['organization'] ?? null) ? $context['organization'] : [];
        $template = trim((string) $request->input('template'));
        $currentSite = $this->applyOrganizationSiteDefaults($this->resolveOrganizationSite($organization), $organization, $template);
        $organizationName = trim((string) ($organization['name'] ?? 'Sua igreja'));

        $publicUrl = $this->sitePublicUrl($currentSite);
        if ($publicUrl !== '') {
            if ($template !== '') {
                $publicUrl .= (str_contains($publicUrl, '?') ? '&' : '?') . 'template=' . rawurlencode($template);
            }
            redirect($publicUrl);
        }

        if (!$currentSite) {
            $currentSite = [
                'site_title' => $organizationName !== '' ? $organizationName : 'Sua igreja',
                'template' => $template !== '' ? $template : 'Institucional Clássico',
                'status' => 'draft',
                'status_label' => 'Preview',
                'slug' => $this->slugifySiteTitle($organizationName !== '' ? $organizationName : 'sua igreja'),
            ];
        }

        if ($template !== '') {
            $currentSite['template'] = $template;
        }

        $settings = $this->organizationSettings($organization, [
            'seo_title',
            'seo_desc',
            'social_instagram',
            'social_facebook',
            'social_youtube',
            'social_whatsapp',
            'appearance_primary',
            'appearance_accent',
        ]);

        $this->view('hub/site-preview', array_merge($context, [
            'pageTitle' => 'Preview do Site — Hub Elo 42',
            'currentSite' => $currentSite,
            'settings' => $settings,
            'previewEvents' => $this->sitePreviewEvents($organization),
            'previewCampaigns' => $this->sitePreviewCampaigns($organization),
            'publishedUrl' => $this->sitePublicUrl($currentSite),
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

        try {
            $this->ensureOrganizationSitesTable();
            $pdo = Database::connection();
            $defaults = $this->siteDefaultsFromOrganization(is_array($organization) ? $organization : [], $template);
            $siteTitle = trim((string) ($defaults['site_title'] ?? ($organization['name'] ?? 'Site institucional')));
            $siteTitle = $siteTitle !== '' ? $siteTitle : 'Site institucional';
            $slug = $this->slugifySiteTitle($siteTitle);

            $stmt = $pdo->prepare("SELECT id FROM organization_sites WHERE organization_id = :org_id LIMIT 1");
            $stmt->execute(['org_id' => (int) $organization['id']]);
            $existingSite = $stmt->fetch();

            if ($existingSite) {
                $stmt = $pdo->prepare("
                    UPDATE organization_sites
                    SET template = :template,
                        site_title = COALESCE(NULLIF(site_title, ''), :site_title),
                        slug = COALESCE(NULLIF(slug, ''), :slug),
                        status = 'draft',
                        generated_at = CURRENT_TIMESTAMP,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE organization_id = :org_id
                ");
                $stmt->execute([
                    'template'   => $template,
                    'site_title' => $siteTitle,
                    'slug'       => $slug,
                    'org_id'     => (int) $organization['id'],
                ]);
                Session::flash('success', 'Site atualizado com sucesso. Modelo: ' . $template);
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO organization_sites (
                        organization_id, template, status, site_title, slug, domain, theme_color,
                        hero_image, logo_image, site_description, about_text, contact_email,
                        contact_phone, whatsapp_url, instagram_url, facebook_url, youtube_url,
                        address_line, city, state, cta_label, cta_url, generated_at, created_at, updated_at
                    ) VALUES (
                        :org_id, :template, 'draft', :site_title, :slug, :domain, :theme_color,
                        :hero_image, :logo_image, :site_description, :about_text, :contact_email,
                        :contact_phone, :whatsapp_url, :instagram_url, :facebook_url, :youtube_url,
                        :address_line, :city, :state, :cta_label, :cta_url, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
                    )
                ");
                $stmt->execute($defaults + [
                    'org_id' => (int) $organization['id'],
                    'template' => $template,
                    'site_title' => $siteTitle,
                    'slug' => $slug,
                ]);
                Session::flash('success', 'Site gerado com sucesso. Seus dados organizacionais foram vinculados ao modelo ' . $template . '.');
            }
        } catch (\Throwable $e) {
            Session::set('hub_generated_site', [
                'template'          => $template,
                'organization_name' => $organization['name'] ?? 'Sua Organização',
                'site_title'        => $organization['name'] ?? 'Site institucional',
                'status'            => 'draft',
                'status_label'      => 'Rascunho',
                'generated_at'      => date('Y-m-d H:i:s'),
                'generated_at_label'=> date('d/m/Y H:i'),
            ]);
            Session::flash('success', 'Site gerado com sucesso em modo local. Modelo: ' . $template . '.');
        }
        
        redirect('/hub/sites');
    }

    public function configurarSite(Request $request): void
    {
        $context = $this->buildBaseContext('Meus Sites', 'sites');
        $organization = $context['organization'];

        if (empty($organization['id'])) {
            Session::flash('warning', 'Cadastre sua organização antes de configurar o site.');
            redirect('/onboarding/organizacao');
        }

        $payload = $this->siteBuilderPayload($request, $organization);

        try {
            $this->ensureOrganizationSitesTable();
            $pdo = Database::connection();
            $stmt = $pdo->prepare('SELECT id FROM organization_sites WHERE organization_id = :org_id LIMIT 1');
            $stmt->execute(['org_id' => (int) $organization['id']]);
            $existingSite = $stmt->fetch();

            if ($existingSite) {
                $stmt = $pdo->prepare("
                    UPDATE organization_sites
                    SET template = :template,
                        status = CASE WHEN status = 'published' THEN 'ready' ELSE status END,
                        site_title = :site_title,
                        slug = :slug,
                        domain = :domain,
                        theme_color = :theme_color,
                        hero_image = :hero_image,
                        logo_image = :logo_image,
                        site_description = :site_description,
                        about_text = :about_text,
                        contact_email = :contact_email,
                        contact_phone = :contact_phone,
                        whatsapp_url = :whatsapp_url,
                        instagram_url = :instagram_url,
                        facebook_url = :facebook_url,
                        youtube_url = :youtube_url,
                        address_line = :address_line,
                        city = :city,
                        state = :state,
                        cta_label = :cta_label,
                        cta_url = :cta_url,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE organization_id = :org_id
                ");
                $stmt->execute($payload + ['org_id' => (int) $organization['id']]);
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO organization_sites (
                        organization_id, template, status, site_title, slug, domain, theme_color,
                        hero_image, logo_image, site_description, about_text, contact_email,
                        contact_phone, whatsapp_url, instagram_url, facebook_url, youtube_url,
                        address_line, city, state, cta_label, cta_url, generated_at, created_at, updated_at
                    ) VALUES (
                        :org_id, :template, 'draft', :site_title, :slug, :domain, :theme_color,
                        :hero_image, :logo_image, :site_description, :about_text, :contact_email,
                        :contact_phone, :whatsapp_url, :instagram_url, :facebook_url, :youtube_url,
                        :address_line, :city, :state, :cta_label, :cta_url, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
                    )
                ");
                $stmt->execute($payload + ['org_id' => (int) $organization['id']]);
            }

            $this->saveAppearanceSettings($request, (int) $organization['id']);

            Session::flash('success', 'Dados do site salvos. Abra o preview para revisar a página gerada.');
        } catch (\Throwable $e) {
            Session::flash('error', 'Não foi possível salvar o site agora. Verifique os dados e tente novamente.');
        }

        redirect('/hub/sites');
    }

    private function saveAppearanceSettings(Request $request, int $orgId): void
    {
        if ($orgId <= 0 || !$this->tableExists('settings')) {
            return;
        }

        $primary = $this->normalizeThemeColor((string) $request->input('appearance_primary', ''));
        $accent = $this->normalizeThemeColor((string) $request->input('appearance_accent', ''));

        if ($primary === '' && $accent === '') {
            return;
        }

        try {
            $pdo = Database::connection();
            $upsert = static function (string $key, string $value) use ($pdo, $orgId): void {
                if ($value === '') return;
                $stmt = $pdo->prepare('SELECT id FROM settings WHERE organization_id = :org AND `key` = :k LIMIT 1');
                $stmt->execute(['org' => $orgId, 'k' => $key]);
                $existing = (int) ($stmt->fetchColumn() ?: 0);
                if ($existing > 0) {
                    $up = $pdo->prepare('UPDATE settings SET value = :v WHERE id = :id');
                    $up->execute(['v' => $value, 'id' => $existing]);
                } else {
                    $ins = $pdo->prepare('INSERT INTO settings (organization_id, `key`, value) VALUES (:org, :k, :v)');
                    $ins->execute(['org' => $orgId, 'k' => $key, 'v' => $value]);
                }
            };
            $upsert('appearance_primary', $primary);
            $upsert('appearance_accent', $accent);
        } catch (\Throwable $e) {
            error_log('[saveAppearanceSettings] ' . $e->getMessage());
        }
    }

    public function publicarSite(Request $request): void
    {
        $context = $this->buildBaseContext('Meus Sites', 'sites');
        $organization = $context['organization'];
        $access = $this->resolveSiteBuilderAccess($organization, $context['user']);

        if (empty($organization['id'])) {
            Session::flash('warning', 'Cadastre sua organização antes de publicar o site.');
            redirect('/onboarding/organizacao');
        }

        if (empty($access['can_publish'])) {
            Session::flash('warning', 'A publicação em domínio real depende da mensalidade ativa do site.');
            redirect('/hub/sites');
        }

        $site = $this->resolveOrganizationSite($organization);
        if (!$site) {
            Session::flash('warning', 'Gere ou configure o site antes de publicar.');
            redirect('/hub/sites');
        }

        $publicUrl = $this->sitePublicUrl($site);

        try {
            $this->ensureOrganizationSitesTable();
            $stmt = Database::connection()->prepare("
                UPDATE organization_sites
                SET status = 'published',
                    published_url = :published_url,
                    published_at = CURRENT_TIMESTAMP,
                    updated_at = CURRENT_TIMESTAMP
                WHERE organization_id = :org_id
            ");
            $stmt->execute([
                'published_url' => $publicUrl,
                'org_id' => (int) $organization['id'],
            ]);

            Session::flash('success', 'Site publicado. A URL já pode ser revisada pela equipe: ' . $publicUrl);
        } catch (\Throwable $e) {
            Session::flash('error', 'Não foi possível publicar o site agora. Tente novamente.');
        }

        redirect('/hub/sites');
    }

    public function expositorIa(Request $request): void
    {
        $context = $this->buildBaseContext('Expositor IA', 'expositor');
        $organization = $context['organization'];
        $user = $context['user'];
        
        // Give the monthly free allowance once per organization/user period.
        $credits = $this->resolveIaCredits($organization, $user);
        $currentPeriod = date('Y-m');
        $hasMonthlyAllowance = $this->hasMonthlyFreeAllowance($organization, $user, $currentPeriod);
        $monthlyAllowanceGranted = false;
        
        if (!$hasMonthlyAllowance) {
            $bonusCredits = 3;
            $this->setIaCredits($organization, $user, $credits + $bonusCredits);
            $this->appendCreditHistory($organization, $user, [
                'date'        => date('d/m/Y H:i'),
                'period'      => $currentPeriod,
                'description' => '3 gerações gratuitas do mês',
                'type'        => 'Gratuito',
                'qty'         => $bonusCredits,
                'price'       => null,
            ]);
            $monthlyAllowanceGranted = true;
            $credits += $bonusCredits;
        }

        $form = Session::getFlash('hub_expositor_form', [
            'passage'      => '',
            'theme'        => '',
            'confessional' => 'biblico-evangelico',
            'depth'        => 'pastoral',
            'content_type' => 'sermon',
            'resource_title' => '',
        ]);

        $this->view('hub/expositor-ia', array_merge($context, [
            'pageTitle'           => 'Expositor IA — Hub Elo 42',
            'iaCredits'           => $credits,
            'iaCreditCost'        => self::IA_CREDIT_COST,
            'canGenerateIa'       => $credits >= self::IA_CREDIT_COST,
            'monthlyAllowanceGranted' => $monthlyAllowanceGranted,
            'expositorLastResult' => Session::getFlash('hub_expositor_result'),
            'expositorGeneratedDraft' => Session::getFlash('hub_expositor_generated'),
            'expositorForm'       => $form,
            'confessionalOptions' => $this->buildConfessionalOptions(),
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
                ['value' => 'product', 'label' => 'Serviço'],
            ],
        ]));
    }

    public function configuracoes(Request $request): void
    {
        $context = $this->buildBaseContext('Configurações', 'configuracoes');
        $subscription = $this->resolveSiteBuilderAccess($context['organization'], $context['user']);
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
            'content_type' => $this->normalizeExpositorContentType(trim((string) $request->input('content_type'))),
            'resource_title' => trim((string) $request->input('resource_title')),
        ];

        if ($form['passage'] === '') {
            Session::flash('error', 'Informe a passagem, tema ou contexto para gerar o material.');
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
        $draft = $this->createExpositorDraft($organization, $user, $form, $result);
        $this->setIaCredits($organization, $user, $credits - self::IA_CREDIT_COST);

        $this->appendCreditHistory($organization, $user, [
            'date'        => date('d/m/Y H:i'),
            'description' => 'Consumo no Expositor IA',
            'type'        => 'Uso',
            'qty'         => -self::IA_CREDIT_COST,
            'price'       => null,
        ]);

        $label = $this->expositorContentTypeLabel($form['content_type']);
        Session::flash('success', $label . ' gerado com sucesso. Foi descontado 1 crédito.');
        Session::flash('hub_expositor_result', $result);
        if ($draft !== null) {
            Session::flash('hub_expositor_generated', $draft);
        }
        Session::flash('hub_expositor_form', $form);
        redirect('/hub/expositor-ia');
    }

    public function publicarExpositorIa(Request $request): void
    {
        $context = $this->buildBaseContext('Expositor IA', 'expositor');
        $organization = $context['organization'];

        $type = $this->normalizeExpositorContentType(trim((string) $request->input('draft_type')));
        $id = (int) $request->input('draft_id');

        if (empty($organization['id']) || $id <= 0) {
            Session::flash('error', 'Não foi possível localizar o material gerado para publicação.');
            redirect('/hub/expositor-ia');
        }

        if ($this->publishExpositorDraft($organization, $type, $id)) {
            Session::flash('success', $this->expositorContentTypeLabel($type) . ' publicado para a área de membros.');
            redirect($this->expositorDestinationUrl($type));
        }

        Session::flash('error', 'Não foi possível publicar esse material agora.');
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
        $allowedTypes = ['church', 'ministry', 'association', 'other'];
        if (!in_array($type, $allowedTypes, true)) {
            $type = (string) ($organization['type'] ?? 'church');
            if (!in_array($type, $allowedTypes, true)) {
                $type = 'church';
            }
        }
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
                'type'  => $type,
                'phone' => $phone,
                'id'    => (int) $organization['id'],
            ]);
        } catch (\Throwable $e) {
            // fallback em sessão
        }

        $sessionOrg = Session::get('organization', []);
        $sessionOrg['name'] = $name;
        $sessionOrg['type'] = $type;
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

    private function resolveSiteBuilderAccess(?array $organization, ?array $user = null): array
    {
        $default = [
            'has_org'             => !empty($organization['id']),
            'plan_name'           => 'Site para Igrejas',
            'billing_cycle'       => null,
            'status'              => 'inactive',
            'status_label'        => 'Sem assinatura ativa',
            'monthly_fee_label'   => 'R$ 67,00/mês',
            'has_active_monthly'  => false,
            'can_access'          => true,
            'can_create'          => true,
            'can_publish'         => false,
            'publish_requirement' => 'Para publicar o site em domínio real, é necessário ativar a mensalidade do construtor.',
        ];

        $user = is_array($user) ? $user : (Session::user() ?? []);
        if (strtolower((string) ($user['email'] ?? '')) === 'ricieri@starmannweb.com.br') {
            return array_merge($default, [
                'status'              => 'granted',
                'status_label'        => 'Acesso livre',
                'monthly_fee_label'   => 'Cortesia Elo 42',
                'has_active_monthly'  => true,
                'can_access'          => true,
                'can_create'          => true,
                'can_publish'         => true,
                'publish_requirement' => 'Acesso liberado sem mensalidade para o administrador Elo 42.',
            ]);
        }

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
                'can_access'          => true,
                'can_create'          => true,
                'can_publish'         => $isActiveMonthly,
                'publish_requirement' => 'Para publicar o site em domínio real, é necessário ativar a mensalidade do construtor.',
            ];
        } catch (\Throwable $e) {
            return $default;
        }
    }

    private function resolveSiteImageInput(string $field, int $orgId, string $fallbackUrl): ?string
    {
        $upload = $_FILES[$field] ?? null;
        if (is_array($upload) && (int) ($upload['error'] ?? \UPLOAD_ERR_NO_FILE) === \UPLOAD_ERR_OK && !empty($upload['tmp_name']) && is_uploaded_file((string) $upload['tmp_name'])) {
            $size = (int) ($upload['size'] ?? 0);
            if ($size > 0 && $size <= 5 * 1024 * 1024) {
                $mime = function_exists('mime_content_type') ? (string) mime_content_type((string) $upload['tmp_name']) : '';
                $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml'];
                if (in_array($mime, $allowedMimes, true)) {
                    $extByMime = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif', 'image/svg+xml' => 'svg'];
                    $ext = $extByMime[$mime];
                    $relativeDir = '/uploads/sites/' . max(0, $orgId);
                    $targetDir = dirname(__DIR__, 3) . '/public' . $relativeDir;
                    if (!is_dir($targetDir)) {
                        @mkdir($targetDir, 0775, true);
                    }
                    $filename = $field . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                    $absolute = $targetDir . '/' . $filename;
                    if (@move_uploaded_file((string) $upload['tmp_name'], $absolute)) {
                        return url($relativeDir . '/' . $filename);
                    }
                }
            }
        }

        return $this->normalizeSiteUrl($fallbackUrl);
    }

    private function siteBuilderPayload(Request $request, array $organization): array
    {
        $siteTitle = trim((string) $request->input('site_title', $organization['name'] ?? 'Site institucional'));
        $siteTitle = $siteTitle !== '' ? $siteTitle : 'Site institucional';
        $domain = trim((string) $request->input('domain', ''));
        $slugSource = $domain !== '' ? preg_replace('/^https?:\/\//i', '', $domain) : $siteTitle;
        $slugSource = (string) preg_replace('/\/.*$/', '', (string) $slugSource);

        $orgId = (int) ($organization['id'] ?? 0);
        $heroImage = $this->resolveSiteImageInput('hero_image', $orgId, (string) $request->input('hero_image', ''));
        $logoImage = $this->resolveSiteImageInput('logo_image', $orgId, (string) $request->input('logo_image', ''));

        return [
            'template' => trim((string) $request->input('template', 'Institucional Clássico')) ?: 'Institucional Clássico',
            'site_title' => $siteTitle,
            'slug' => $this->slugifySiteTitle($slugSource),
            'domain' => $this->nullableText($domain),
            'theme_color' => $this->normalizeThemeColor((string) $request->input('theme_color', '#0A4DFF')),
            'hero_image' => $heroImage,
            'logo_image' => $logoImage,
            'site_description' => $this->nullableText((string) $request->input('site_description', '')),
            'about_text' => $this->nullableText((string) $request->input('about_text', '')),
            'contact_email' => $this->nullableText((string) $request->input('contact_email', '')),
            'contact_phone' => $this->nullableText((string) $request->input('contact_phone', '')),
            'whatsapp_url' => $this->normalizeSiteUrl((string) $request->input('whatsapp_url', '')),
            'instagram_url' => $this->normalizeSiteUrl((string) $request->input('instagram_url', '')),
            'facebook_url' => $this->normalizeSiteUrl((string) $request->input('facebook_url', '')),
            'youtube_url' => $this->normalizeSiteUrl((string) $request->input('youtube_url', '')),
            'address_line' => $this->nullableText((string) $request->input('address_line', '')),
            'city' => $this->nullableText((string) $request->input('city', '')),
            'state' => $this->nullableText((string) $request->input('state', '')),
            'cta_label' => $this->nullableText((string) $request->input('cta_label', '')),
            'cta_url' => $this->normalizeSiteUrl((string) $request->input('cta_url', '')),
        ];
    }

    private function applyOrganizationSiteDefaults(?array $site, array $organization, string $template = ''): ?array
    {
        if (!$site && empty($organization)) {
            return null;
        }

        $defaults = $this->siteDefaultsFromOrganization($organization, $template);

        if (!$site) {
            $site = $defaults;
            $site['status'] = 'draft';
            $site['status_label'] = 'Preview';
            $site['generated_at_label'] = 'Dados cadastrais';
            $site['is_preview_only'] = true;
        } else {
            foreach ($defaults as $field => $value) {
                $current = $site[$field] ?? null;
                if (($current === null || trim((string) $current) === '') && $value !== null && trim((string) $value) !== '') {
                    $site[$field] = $value;
                }
            }

            $site['status_label'] = $site['status_label'] ?? $this->formatSiteStatusLabel((string) ($site['status'] ?? 'draft'));
            $site['generated_at_label'] = $site['generated_at_label'] ?? $this->formatDateTimeLabel((string) ($site['generated_at'] ?? ''));
        }

        if ($template !== '') {
            $site['template'] = $template;
        }

        $site['public_url'] = $this->sitePublicUrl($site);

        return $site;
    }

    private function siteDefaultsFromOrganization(array $organization, string $template = ''): array
    {
        $settings = $this->organizationSettings($organization, [
            'seo_title',
            'seo_desc',
            'social_instagram',
            'social_facebook',
            'social_youtube',
            'social_whatsapp',
            'appearance_primary',
        ]);

        $jsonSettings = [];
        if (!empty($organization['settings'])) {
            $decoded = json_decode((string) $organization['settings'], true);
            $jsonSettings = is_array($decoded) ? $decoded : [];
        }

        $fromSettings = static function (string $key) use ($settings, $jsonSettings): string {
            return trim((string) ($settings[$key] ?? $jsonSettings[$key] ?? ''));
        };

        $siteTitle = trim((string) ($fromSettings('seo_title') ?: ($organization['name'] ?? 'Site institucional')));
        $siteTitle = $siteTitle !== '' ? $siteTitle : 'Site institucional';
        $phone = trim((string) ($organization['phone'] ?? ''));
        $whatsapp = $fromSettings('social_whatsapp');

        if ($whatsapp === '' && $phone !== '') {
            $digits = preg_replace('/\D+/', '', $phone) ?? '';
            if ($digits !== '') {
                $whatsapp = 'https://wa.me/' . (str_starts_with($digits, '55') ? $digits : '55' . ltrim($digits, '0'));
            }
        }

        $domain = trim((string) ($organization['website'] ?? ''));
        $slugSource = $domain !== '' ? preg_replace('/^https?:\/\//i', '', $domain) : $siteTitle;
        $slugSource = (string) preg_replace('/\/.*$/', '', (string) $slugSource);

        return [
            'template' => $template !== '' ? $template : 'Institucional Clássico',
            'site_title' => $siteTitle,
            'slug' => $this->slugifySiteTitle($slugSource),
            'domain' => $this->nullableText($domain),
            'theme_color' => $this->normalizeThemeColor($fromSettings('appearance_primary') ?: '#0A4DFF'),
            'hero_image' => null,
            'logo_image' => $this->normalizeSiteUrl((string) ($organization['logo'] ?? '')),
            'site_description' => $this->nullableText($fromSettings('seo_desc') ?: 'Uma página institucional para acolher visitantes, apresentar a igreja e facilitar o contato.'),
            'about_text' => $this->nullableText('Conheça a comunidade, acompanhe eventos, ministérios e campanhas cadastradas pela igreja.'),
            'contact_email' => $this->nullableText((string) ($organization['email'] ?? '')),
            'contact_phone' => $this->nullableText($phone),
            'whatsapp_url' => $this->normalizeSiteUrl($whatsapp),
            'instagram_url' => $this->normalizeSiteUrl($fromSettings('social_instagram')),
            'facebook_url' => $this->normalizeSiteUrl($fromSettings('social_facebook')),
            'youtube_url' => $this->normalizeSiteUrl($fromSettings('social_youtube')),
            'address_line' => $this->nullableText((string) ($organization['address'] ?? '')),
            'city' => $this->nullableText((string) ($organization['city'] ?? '')),
            'state' => $this->nullableText((string) ($organization['state'] ?? '')),
            'cta_label' => $whatsapp !== '' ? 'Falar no WhatsApp' : 'Falar com a igreja',
            'cta_url' => $this->normalizeSiteUrl($whatsapp),
        ];
    }

    private function normalizeThemeColor(string $value): string
    {
        $value = trim($value);
        return preg_match('/^#[0-9a-fA-F]{6}$/', $value) ? $value : '#0A4DFF';
    }

    private function normalizeSiteUrl(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        if (str_starts_with($value, '/') || preg_match('/^https?:\/\//i', $value)) {
            return $value;
        }

        if (str_starts_with($value, 'wa.me/') || str_contains($value, '.')) {
            return 'https://' . ltrim($value, '/');
        }

        return $value;
    }

    private function nullableText(string $value): ?string
    {
        $value = trim($value);
        return $value === '' ? null : $value;
    }

    private function sitePublicUrl(?array $site): string
    {
        if (!$site) {
            return '';
        }

        $domain = trim((string) ($site['domain'] ?? ''));
        if ($domain !== '') {
            return preg_match('/^https?:\/\//i', $domain) ? $domain : 'https://' . $domain;
        }

        $slug = trim((string) ($site['slug'] ?? ''));
        if ($slug === '') {
            return '';
        }

        return url('/site/' . rawurlencode($slug));
    }

    private function buildSitePublishChecklist(?array $organization, ?array $site, array $access): array
    {
        $organizationName = trim((string) ($organization['name'] ?? ''));
        $hasSite = is_array($site) && !empty($site);
        $hasContact = $hasSite && (
            trim((string) ($site['contact_phone'] ?? '')) !== ''
            || trim((string) ($site['contact_email'] ?? '')) !== ''
            || trim((string) ($site['whatsapp_url'] ?? '')) !== ''
        );
        $hasImages = $hasSite && (
            trim((string) ($site['hero_image'] ?? '')) !== ''
            || trim((string) ($site['logo_image'] ?? '')) !== ''
        );
        $hasSocial = $hasSite && (
            trim((string) ($site['instagram_url'] ?? '')) !== ''
            || trim((string) ($site['facebook_url'] ?? '')) !== ''
            || trim((string) ($site['youtube_url'] ?? '')) !== ''
        );

        return [
            [
                'done' => $organizationName !== '',
                'title' => 'Dados da organização',
                'text' => $organizationName !== '' ? $organizationName . ' será usada como base.' : 'Complete o cadastro da organização.',
            ],
            [
                'done' => $hasSite && trim((string) ($site['site_title'] ?? '')) !== '' && trim((string) ($site['site_description'] ?? '')) !== '',
                'title' => 'Texto do site',
                'text' => 'Título, chamada principal e resumo institucional.',
            ],
            [
                'done' => $hasImages,
                'title' => 'Imagens',
                'text' => 'Logo e imagem principal ajudam o site a ficar publicável.',
            ],
            [
                'done' => $hasContact,
                'title' => 'Contato',
                'text' => 'Telefone, e-mail ou WhatsApp para visitantes.',
            ],
            [
                'done' => $hasSocial,
                'title' => 'Redes sociais',
                'text' => 'Instagram, Facebook ou YouTube oficiais.',
            ],
            [
                'done' => !empty($access['can_publish']),
                'title' => 'Mensalidade de publicação',
                'text' => !empty($access['can_publish']) ? 'Publicação liberada.' : 'Ative a mensalidade para domínio real.',
            ],
        ];
    }

    private function ensureOrganizationSitesTable(): void
    {
        $pdo = Database::connection();
        $driver = (string) $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);

        if ($driver === 'sqlite') {
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS organization_sites (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    organization_id INTEGER NOT NULL UNIQUE,
                    template TEXT NOT NULL DEFAULT 'Institucional Clássico',
                    status TEXT NOT NULL DEFAULT 'draft',
                    site_title TEXT NULL,
                    slug TEXT NULL,
                    domain TEXT NULL,
                    theme_color TEXT DEFAULT '#0A4DFF',
                    hero_image TEXT NULL,
                    logo_image TEXT NULL,
                    site_description TEXT NULL,
                    about_text TEXT NULL,
                    contact_email TEXT NULL,
                    contact_phone TEXT NULL,
                    whatsapp_url TEXT NULL,
                    instagram_url TEXT NULL,
                    facebook_url TEXT NULL,
                    youtube_url TEXT NULL,
                    address_line TEXT NULL,
                    city TEXT NULL,
                    state TEXT NULL,
                    cta_label TEXT NULL,
                    cta_url TEXT NULL,
                    published_url TEXT NULL,
                    generated_at TEXT DEFAULT CURRENT_TIMESTAMP,
                    published_at TEXT NULL,
                    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                    updated_at TEXT DEFAULT CURRENT_TIMESTAMP
                )
            ");
            $pdo->exec("CREATE INDEX IF NOT EXISTS idx_organization_sites_org ON organization_sites (organization_id)");
            $this->ensureOrganizationSiteColumns($pdo, $driver);
            return;
        }

        if ($driver === 'pgsql') {
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS organization_sites (
                    id BIGSERIAL PRIMARY KEY,
                    organization_id BIGINT NOT NULL UNIQUE,
                    template VARCHAR(120) NOT NULL DEFAULT 'Institucional Clássico',
                    status VARCHAR(40) NOT NULL DEFAULT 'draft',
                    site_title VARCHAR(255) NULL,
                    slug VARCHAR(255) NULL,
                    domain VARCHAR(255) NULL,
                    theme_color VARCHAR(20) DEFAULT '#0A4DFF',
                    hero_image VARCHAR(500) NULL,
                    logo_image VARCHAR(500) NULL,
                    site_description TEXT NULL,
                    about_text TEXT NULL,
                    contact_email VARCHAR(180) NULL,
                    contact_phone VARCHAR(40) NULL,
                    whatsapp_url VARCHAR(500) NULL,
                    instagram_url VARCHAR(500) NULL,
                    facebook_url VARCHAR(500) NULL,
                    youtube_url VARCHAR(500) NULL,
                    address_line VARCHAR(500) NULL,
                    city VARCHAR(120) NULL,
                    state VARCHAR(80) NULL,
                    cta_label VARCHAR(120) NULL,
                    cta_url VARCHAR(500) NULL,
                    published_url VARCHAR(500) NULL,
                    generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    published_at TIMESTAMP NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            $pdo->exec("CREATE INDEX IF NOT EXISTS idx_organization_sites_org ON organization_sites (organization_id)");
            $this->ensureOrganizationSiteColumns($pdo, $driver);
            return;
        }

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS organization_sites (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                organization_id BIGINT UNSIGNED NOT NULL,
                template VARCHAR(120) NOT NULL DEFAULT 'Institucional Clássico',
                status VARCHAR(40) NOT NULL DEFAULT 'draft',
                site_title VARCHAR(255) NULL,
                slug VARCHAR(255) NULL,
                domain VARCHAR(255) NULL,
                theme_color VARCHAR(20) DEFAULT '#0A4DFF',
                hero_image VARCHAR(500) NULL,
                logo_image VARCHAR(500) NULL,
                site_description TEXT NULL,
                about_text TEXT NULL,
                contact_email VARCHAR(180) NULL,
                contact_phone VARCHAR(40) NULL,
                whatsapp_url VARCHAR(500) NULL,
                instagram_url VARCHAR(500) NULL,
                facebook_url VARCHAR(500) NULL,
                youtube_url VARCHAR(500) NULL,
                address_line VARCHAR(500) NULL,
                city VARCHAR(120) NULL,
                state VARCHAR(80) NULL,
                cta_label VARCHAR(120) NULL,
                cta_url VARCHAR(500) NULL,
                published_url VARCHAR(500) NULL,
                generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                published_at TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_organization_sites_org (organization_id),
                UNIQUE KEY uk_organization_sites_org (organization_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        $this->ensureOrganizationSiteColumns($pdo, $driver);
    }

    private function ensureOrganizationSiteColumns(\PDO $pdo, string $driver): void
    {
        $definitions = [
            'sqlite' => [
                'site_description' => 'TEXT NULL',
                'about_text' => 'TEXT NULL',
                'contact_email' => 'TEXT NULL',
                'contact_phone' => 'TEXT NULL',
                'whatsapp_url' => 'TEXT NULL',
                'instagram_url' => 'TEXT NULL',
                'facebook_url' => 'TEXT NULL',
                'youtube_url' => 'TEXT NULL',
                'address_line' => 'TEXT NULL',
                'city' => 'TEXT NULL',
                'state' => 'TEXT NULL',
                'cta_label' => 'TEXT NULL',
                'cta_url' => 'TEXT NULL',
                'published_url' => 'TEXT NULL',
            ],
            'default' => [
                'site_description' => 'TEXT NULL',
                'about_text' => 'TEXT NULL',
                'contact_email' => 'VARCHAR(180) NULL',
                'contact_phone' => 'VARCHAR(40) NULL',
                'whatsapp_url' => 'VARCHAR(500) NULL',
                'instagram_url' => 'VARCHAR(500) NULL',
                'facebook_url' => 'VARCHAR(500) NULL',
                'youtube_url' => 'VARCHAR(500) NULL',
                'address_line' => 'VARCHAR(500) NULL',
                'city' => 'VARCHAR(120) NULL',
                'state' => 'VARCHAR(80) NULL',
                'cta_label' => 'VARCHAR(120) NULL',
                'cta_url' => 'VARCHAR(500) NULL',
                'published_url' => 'VARCHAR(500) NULL',
            ],
        ];

        $columns = $definitions[$driver] ?? $definitions['default'];

        foreach ($columns as $column => $definition) {
            if ($this->databaseColumnExists($pdo, 'organization_sites', $column, $driver)) {
                continue;
            }

            try {
                $pdo->exec('ALTER TABLE organization_sites ADD COLUMN ' . $column . ' ' . $definition);
            } catch (\Throwable $e) {
                // Migrations also add these fields; ignore if another process already created the column.
            }
        }
    }

    private function databaseColumnExists(\PDO $pdo, string $table, string $column, string $driver): bool
    {
        try {
            if ($driver === 'sqlite') {
                $stmt = $pdo->query('PRAGMA table_info(' . $table . ')');
                foreach ($stmt->fetchAll() as $row) {
                    if (strcasecmp((string) ($row['name'] ?? ''), $column) === 0) {
                        return true;
                    }
                }
                return false;
            }

            if ($driver === 'pgsql') {
                $stmt = $pdo->prepare('SELECT column_name FROM information_schema.columns WHERE table_name = :table AND column_name = :column LIMIT 1');
                $stmt->execute(['table' => $table, 'column' => $column]);
                return (bool) $stmt->fetchColumn();
            }

            $stmt = $pdo->prepare('SHOW COLUMNS FROM ' . $table . ' LIKE :column');
            $stmt->execute(['column' => $column]);
            return (bool) $stmt->fetchColumn();
        } catch (\Throwable $e) {
            return true;
        }
    }

    private function resolveOrganizationSite(?array $organization): ?array
    {
        if (!empty($organization['id'])) {
            try {
                $this->ensureOrganizationSitesTable();
                $stmt = Database::connection()->prepare('SELECT * FROM organization_sites WHERE organization_id = :org_id LIMIT 1');
                $stmt->execute(['org_id' => (int) $organization['id']]);
                $site = $stmt->fetch();

                if (is_array($site) && !empty($site)) {
                    $site['status_label'] = $this->formatSiteStatusLabel((string) ($site['status'] ?? 'draft'));
                    $site['generated_at_label'] = $this->formatDateTimeLabel((string) ($site['generated_at'] ?? ''));
                    $site['published_at_label'] = $this->formatDateTimeLabel((string) ($site['published_at'] ?? ''));
                    $site['public_url'] = $this->sitePublicUrl($site);
                    return $site;
                }
            } catch (\Throwable $e) {
                // fallback abaixo
            }
        }

        $sessionSite = Session::get('hub_generated_site');
        if (!is_array($sessionSite)) {
            return null;
        }

        $sessionSite['status_label'] = $sessionSite['status_label'] ?? $this->formatSiteStatusLabel((string) ($sessionSite['status'] ?? 'draft'));
        $sessionSite['generated_at_label'] = $sessionSite['generated_at_label'] ?? $this->formatDateTimeLabel((string) ($sessionSite['generated_at'] ?? ''));

        return $sessionSite;
    }

    private function organizationSettings(?array $organization, array $keys = []): array
    {
        if (empty($organization['id']) || !$this->tableExists('settings')) {
            return [];
        }

        $params = ['org_id' => (int) $organization['id']];
        $sql = 'SELECT `key`, value FROM settings WHERE organization_id = :org_id';

        if (!empty($keys)) {
            $placeholders = [];
            foreach (array_values($keys) as $index => $key) {
                $param = 'setting_' . $index;
                $placeholders[] = ':' . $param;
                $params[$param] = (string) $key;
            }
            $sql .= ' AND `key` IN (' . implode(',', $placeholders) . ')';
        }

        $settings = [];
        foreach ($this->fetchRows($sql, $params) as $row) {
            $settings[(string) ($row['key'] ?? '')] = (string) ($row['value'] ?? '');
        }

        return $settings;
    }

    private function sitePreviewEvents(?array $organization): array
    {
        if (empty($organization['id']) || !$this->tableExists('events')) {
            return [];
        }

        return $this->fetchRows(
            "SELECT title, description, location, start_date
             FROM events
             WHERE organization_id = :org_id AND status IN ('published', 'ongoing', 'draft')
             ORDER BY start_date ASC
             LIMIT 3",
            ['org_id' => (int) $organization['id']]
        );
    }

    private function sitePreviewCampaigns(?array $organization): array
    {
        if (empty($organization['id']) || !$this->tableExists('campaigns')) {
            return [];
        }

        return $this->fetchRows(
            "SELECT title, description, goal_amount, raised_amount, designation
             FROM campaigns
             WHERE organization_id = :org_id AND status IN ('active', 'published', 'completed')
             ORDER BY created_at DESC
             LIMIT 3",
            ['org_id' => (int) $organization['id']]
        );
    }

    private function fetchRows(string $sql, array $params = []): array
    {
        try {
            $stmt = Database::connection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function tableExists(string $table): bool
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

    private function formatSiteStatusLabel(string $status): string
    {
        return match ($status) {
            'ready' => 'Pronto para revisão',
            'published' => 'Publicado',
            'blocked' => 'Bloqueado',
            default => 'Rascunho',
        };
    }

    private function formatDateTimeLabel(string $value): string
    {
        if (trim($value) === '') {
            return 'Ainda não gerado';
        }

        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return $value;
        }

        return date('d/m/Y H:i', $timestamp);
    }

    private function slugifySiteTitle(string $value): string
    {
        $value = trim($value);
        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
            if (is_string($converted) && $converted !== '') {
                $value = $converted;
            }
        }

        $slug = strtolower((string) preg_replace('/[^a-zA-Z0-9]+/', '-', $value));
        $slug = trim($slug, '-');

        return $slug !== '' ? $slug : 'site-institucional';
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
            ['id' => 'starter', 'name' => 'Pacote Expositor', 'credits' => 30, 'price_label' => 'R$ 34,90', 'description' => 'Créditos para sermões avulsos, estudos bíblicos e aulas EBD.', 'badge' => '', 'badge_type' => ''],
            ['id' => 'pro', 'name' => 'Pacote Ministerial', 'credits' => 120, 'price_label' => 'R$ 97,00', 'description' => 'Melhor custo-benefício para planejamento recorrente no ministério.', 'badge' => 'Mais vendido', 'badge_type' => 'hot'],
            ['id' => 'max', 'name' => 'Pacote Intensivo', 'credits' => 300, 'price_label' => 'R$ 197,00', 'description' => 'Volume para equipes, séries, discipulados e materiais mensais.', 'badge' => 'Melhor valor', 'badge_type' => 'new'],
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

    private function hasMonthlyFreeAllowance(?array $organization, array $user, string $period): bool
    {
        foreach ($this->resolveCreditHistory($organization, $user) as $entry) {
            if (!is_array($entry) || ($entry['type'] ?? '') !== 'Gratuito') {
                continue;
            }

            if (($entry['period'] ?? '') === $period) {
                return true;
            }

            $date = \DateTimeImmutable::createFromFormat('d/m/Y H:i', (string) ($entry['date'] ?? ''));
            if ($date instanceof \DateTimeImmutable && $date->format('Y-m') === $period) {
                return true;
            }
        }

        return false;
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
            [
                'name' => 'Institucional Clássico',
                'description' => 'Home, sobre, ministérios, agenda, ofertas e contato em um site institucional limpo.',
                'status' => 'Disponível',
                'highlight' => true,
                'assets' => ['Logo', 'Foto principal da igreja', 'Fotos dos ministérios'],
            ],
            [
                'name' => 'Comunidade Engajada',
                'description' => 'Inspirado no padrão Igreja do Porto: hero com chamada, princípios, última mensagem, séries, eventos e blocos de convite (visite, voluntariado, discipulado, café com pastor).',
                'status' => 'Disponível',
                'highlight' => false,
                'assets' => ['Foto da comunidade', 'Capas de séries', 'Cards de eventos', 'Banners de convite'],
            ],
            [
                'name' => 'Campanhas e Eventos',
                'description' => 'Páginas para congressos, conferências, campanhas e inscrições com chamada direta.',
                'status' => 'Disponível',
                'highlight' => false,
                'assets' => ['Banner do evento', 'Imagem dos palestrantes', 'Identidade da campanha'],
            ],
            [
                'name' => 'Captação para ONGs',
                'description' => 'Apresentação institucional, projetos, impacto social, doações e captação de leads.',
                'status' => 'Disponível',
                'highlight' => false,
                'assets' => ['Fotos dos projetos', 'Dados de impacto', 'Marca institucional'],
            ],
        ];
    }

    private function buildConfessionalOptions(): array
    {
        return [
            ['value' => 'somente-biblico', 'label' => 'Somente bíblica'],
            ['value' => 'biblico-evangelico', 'label' => 'Bíblica evangélica'],
            ['value' => 'reformada-calvinista', 'label' => 'Reformada / Calvinista'],
            ['value' => 'westminster', 'label' => 'Presbiteriana — Westminster'],
            ['value' => 'londres-1689', 'label' => 'Batista Reformada — Londres 1689'],
            ['value' => 'batista-historica', 'label' => 'Batista histórica'],
            ['value' => 'batista-tradicional', 'label' => 'Batista tradicional brasileira'],
            ['value' => 'congregacional', 'label' => 'Congregacional'],
            ['value' => 'arminiana-classica', 'label' => 'Arminiana clássica'],
            ['value' => 'wesleyana-metodista', 'label' => 'Wesleyana / Metodista'],
            ['value' => 'nazareno-holiness', 'label' => 'Nazareno / Santidade'],
            ['value' => 'pentecostal-classica', 'label' => 'Pentecostal clássica'],
            ['value' => 'assembleiana', 'label' => 'Pentecostal assembleiana'],
            ['value' => 'quadrangular', 'label' => 'Pentecostal quadrangular'],
            ['value' => 'carismatica-renovada', 'label' => 'Carismática / Renovada'],
            ['value' => 'neopentecostal', 'label' => 'Neopentecostal'],
            ['value' => 'luterana', 'label' => 'Luterana'],
            ['value' => 'anglicana', 'label' => 'Anglicana'],
            ['value' => 'anabatista-menonita', 'label' => 'Anabatista / Menonita'],
            ['value' => 'adventista', 'label' => 'Adventista'],
            ['value' => 'dispensacionalista', 'label' => 'Dispensacionalista'],
            ['value' => 'aliancista', 'label' => 'Teologia da Aliança'],
            ['value' => 'contextual-brasileira', 'label' => 'Evangélica brasileira contextual'],
        ];
    }

    private function formatConfessionalLabel(string $value): string
    {
        foreach ($this->buildConfessionalOptions() as $option) {
            if (($option['value'] ?? '') === $value) {
                return (string) ($option['label'] ?? 'Bíblica evangélica');
            }
        }

        return 'Bíblica evangélica';
    }

    private function normalizeExpositorContentType(string $type): string
    {
        return match ($type) {
            'study' => 'study',
            'reading_plan' => 'reading_plan',
            'resource' => 'resource',
            default => 'sermon',
        };
    }

    private function expositorContentTypeLabel(string $type): string
    {
        return match ($this->normalizeExpositorContentType($type)) {
            'study' => 'Estudo',
            'reading_plan' => 'Plano de leitura',
            'resource' => 'Recurso ministerial',
            default => 'Sermão',
        };
    }

    private function expositorDestinationUrl(string $type): string
    {
        return $this->normalizeExpositorContentType($type) === 'reading_plan'
            ? '/gestao/plano-leitura'
            : '/gestao/sermoes';
    }

    private function expositorDraftTitle(array $form): string
    {
        $theme = trim((string) ($form['theme'] ?? ''));
        $passage = trim((string) ($form['passage'] ?? ''));
        $resourceTitle = trim((string) ($form['resource_title'] ?? ''));
        $type = $this->normalizeExpositorContentType((string) ($form['content_type'] ?? 'sermon'));

        if ($type === 'resource' && $resourceTitle !== '') {
            return $theme !== '' ? $resourceTitle . ': ' . $theme : $resourceTitle;
        }

        if ($theme !== '') {
            return $theme;
        }

        return match ($type) {
            'study' => 'Estudo bíblico sobre ' . ($passage !== '' ? $passage : 'texto bíblico'),
            'reading_plan' => 'Plano de leitura: ' . ($passage !== '' ? $passage : 'jornada bíblica'),
            'resource' => $resourceTitle !== '' ? $resourceTitle : 'Recurso ministerial',
            default => 'Sermão sobre ' . ($passage !== '' ? $passage : 'texto bíblico'),
        };
    }

    private function createExpositorDraft(?array $organization, array $user, array $form, string $result): ?array
    {
        if (empty($organization['id'])) {
            return null;
        }

        $type = $this->normalizeExpositorContentType((string) ($form['content_type'] ?? 'sermon'));
        $title = $this->expositorDraftTitle($form);
        $now = date('Y-m-d H:i:s');

        try {
            $pdo = Database::connection();

            if ($type === 'reading_plan') {
                if (!$this->tableExists('reading_plans')) {
                    return null;
                }

                $stmt = $pdo->prepare(
                    'INSERT INTO reading_plans (organization_id, title, description, duration_days, book_range, participants_count, status, created_at, updated_at)
                     VALUES (:organization_id, :title, :description, :duration_days, :book_range, :participants_count, :status, :created_at, :updated_at)'
                );
                $stmt->execute([
                    'organization_id' => (int) $organization['id'],
                    'title' => $title,
                    'description' => $result,
                    'duration_days' => 30,
                    'book_range' => trim((string) ($form['passage'] ?? '')) ?: 'Bíblia',
                    'participants_count' => 0,
                    'status' => 'draft',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            } else {
                if (!$this->tableExists('sermons')) {
                    return null;
                }

                $reference = trim((string) ($form['passage'] ?? ''));
                $label = $this->expositorContentTypeLabel($type);
                $stmt = $pdo->prepare(
                    'INSERT INTO sermons (organization_id, title, preacher, sermon_date, bible_reference, summary, series_name, tags, status, created_at, updated_at)
                     VALUES (:organization_id, :title, :preacher, :sermon_date, :bible_reference, :summary, :series_name, :tags, :status, :created_at, :updated_at)'
                );
                $stmt->execute([
                    'organization_id' => (int) $organization['id'],
                    'title' => $title,
                    'preacher' => trim((string) ($user['name'] ?? '')) ?: 'Expositor IA',
                    'sermon_date' => date('Y-m-d'),
                    'bible_reference' => $reference,
                    'summary' => $result,
                    'series_name' => $type === 'resource' ? trim((string) ($form['resource_title'] ?? '')) : null,
                    'tags' => 'Expositor IA, ' . $label,
                    'status' => 'draft',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            return [
                'type' => $type,
                'id' => (int) $pdo->lastInsertId(),
                'title' => $title,
                'label' => $this->expositorContentTypeLabel($type),
                'destination' => $this->expositorDestinationUrl($type),
            ];
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function publishExpositorDraft(?array $organization, string $type, int $id): bool
    {
        if (empty($organization['id']) || $id <= 0) {
            return false;
        }

        $type = $this->normalizeExpositorContentType($type);
        $table = $type === 'reading_plan' ? 'reading_plans' : 'sermons';
        $status = $type === 'reading_plan' ? 'active' : 'published';

        if (!$this->tableExists($table)) {
            return false;
        }

        try {
            $stmt = Database::connection()->prepare(
                "UPDATE {$table}
                 SET status = :status, updated_at = :updated_at
                 WHERE id = :id AND organization_id = :organization_id"
            );
            $stmt->execute([
                'status' => $status,
                'updated_at' => date('Y-m-d H:i:s'),
                'id' => $id,
                'organization_id' => (int) $organization['id'],
            ]);

            return $stmt->rowCount() > 0;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function buildExpositorResult(array $form): string
    {
        $passage = $form['passage'] !== '' ? $form['passage'] : 'Passagem não informada';
        $theme = $form['theme'] !== '' ? $form['theme'] : 'Tema livre';
        $confessionalLabel = $this->formatConfessionalLabel((string) ($form['confessional'] ?? 'biblico-evangelico'));
        $type = $this->normalizeExpositorContentType((string) ($form['content_type'] ?? 'sermon'));
        $contentLabel = $this->expositorContentTypeLabel($type);
        $resourceTitle = trim((string) ($form['resource_title'] ?? ''));

        $depthLabel = match ((string) ($form['depth'] ?? 'pastoral')) {
            'teologico' => 'Aprofundamento Teológico',
            'academico' => 'Exegese Acadêmica',
            default => 'Sermão Expositivo Pastoral',
        };

        $resourceLine = $resourceTitle !== '' ? "Recurso: {$resourceTitle}\n" : '';

        return "Tipo de material: {$contentLabel}\n"
            . $resourceLine
            . "Passagem/contexto: {$passage}\n"
            . "Tema/Ênfase: {$theme}\n"
            . "Linha teológica: {$confessionalLabel}\n"
            . "Nível de profundidade: {$depthLabel}\n\n"
            . "1. Caminho exegético\n"
            . "- Contexto histórico: identifique autor, audiência original, ocasião e tensão pastoral da perícope.\n"
            . "- Estrutura literária: observe movimentos do argumento, repetições, contrastes e conectivos.\n"
            . "- Palavras-chave: destaque termos relevantes no português e, quando necessário, investigue hebraico/grego com sobriedade.\n"
            . "- Teologia do texto: formule a verdade central antes de buscar aplicações.\n"
            . "- Eixo cristológico: mostre como o texto se conecta ao evangelho sem forçar alegorias.\n"
            . "- Ênfase confessional: revise o conteúdo à luz da linha escolhida, mantendo a Escritura como norma final.\n\n"
            . "2. Ponte de revisão pastoral\n"
            . "- Tema central para aprovar: {$theme}\n"
            . "- Pergunta de controle: o sermão nasce do texto ou apenas usa o texto como pretexto?\n"
            . "- Decisão pastoral: ajuste tom, público, ilustrações e chamada à resposta antes de desenvolver o material.\n\n"
            . "3. Desenvolvimento sugerido\n"
            . "- Introdução: apresente a dor pastoral que o texto confronta.\n"
            . "- Movimento 1: exponha a verdade bíblica principal.\n"
            . "- Movimento 2: mostre as implicações para fé, comunidade e obediência.\n"
            . "- Movimento 3: aplique com clareza, esperança e responsabilidade espiritual.\n"
            . "- Conclusão: conduza a igreja à resposta apropriada: arrependimento, fé, gratidão, missão ou perseverança.\n\n"
            . "4. Materiais derivados\n"
            . "- Sermão: organize em introdução, proposição, pontos, aplicação e conclusão.\n"
            . "- Pequeno grupo: crie 5 perguntas de observação, interpretação e aplicação.\n"
            . "- EBD/discipulado: transforme o estudo em aula com objetivo, roteiro e tarefa prática.\n"
            . "- Série: avalie se a passagem abre uma sequência de 3 a 6 mensagens.\n\n"
            . "Nota pastoral\n"
            . "Use este resultado como apoio de estudo. A oração, a responsabilidade pastoral, a revisão doutrinária e o conhecimento da igreja local continuam indispensáveis.";
    }

    private function buildShowcaseItems(): array
    {
        $wa = fn (string $service): string => $this->buildCommercialWhatsAppUrl($service);

        return [
            ['icon' => 'monitor', 'title' => 'Painel de Gestão de Igrejas', 'description' => 'Acesso completo para membros, eventos, financeiro e rotina ministerial com 7 dias gratuitos.', 'price' => 'R$ 49,90/mês (7 dias grátis)', 'badge' => 'Mais vendido', 'badge_type' => 'hot', 'cta' => 'Ver detalhes', 'url' => url('/gestao')],
            ['icon' => 'book', 'title' => 'Expositor IA', 'description' => 'Geração de esboços e estudos bíblicos para apoio pastoral e ministerial.', 'price' => 'Use com créditos', 'badge' => 'Novo', 'badge_type' => 'new', 'cta' => 'Comprar créditos', 'url' => url('/hub/creditos')],
            ['icon' => 'gift', 'title' => 'Google Ad Grants', 'description' => 'Implantação e aprovação para captar até US$ 10.000/mês em anúncios.', 'price' => 'R$ 497,00', 'badge' => '', 'badge_type' => '', 'cta' => 'Falar com comercial', 'url' => $wa('Google Ad Grants')],
            ['icon' => 'gift', 'title' => 'Google para ONGs', 'description' => 'Trilha guiada para aprovação e criação do Google Workspace gratuito.', 'price' => 'R$ 297,00', 'badge' => 'Novo', 'badge_type' => 'new', 'cta' => 'Falar com comercial', 'url' => $wa('Google para ONGs')],
            ['icon' => 'megaphone', 'title' => 'Gestão de Tráfego Pago', 'description' => 'Planejamento e operação de campanhas para ampliar alcance e resultados.', 'price' => 'Consulte', 'badge' => 'Novo', 'badge_type' => 'new', 'cta' => 'Falar com comercial', 'url' => $wa('Gestão de Tráfego Pago')],
            ['icon' => 'briefcase', 'title' => 'TechSoup Brasil', 'description' => 'Registro e validação para liberar benefícios de filantropia digital.', 'price' => 'R$ 197,00', 'badge' => '', 'badge_type' => '', 'cta' => 'Falar com comercial', 'url' => $wa('TechSoup Brasil')],
            ['icon' => 'briefcase', 'title' => 'Microsoft, Canva e Slack para ONGs', 'description' => 'Liberação de contas premium para ganho real de produtividade.', 'price' => 'R$ 147,00', 'badge' => 'Novo', 'badge_type' => 'new', 'cta' => 'Falar com comercial', 'url' => $wa('Microsoft, Canva e Slack para ONGs')],
            ['icon' => 'globe', 'title' => 'Site para Igrejas', 'description' => 'Sites profissionais para publicação com identidade visual da organização.', 'price' => 'R$ 67,00/mês', 'badge' => '', 'badge_type' => '', 'cta' => 'Ver detalhes', 'url' => url('/hub/sites')],
            ['icon' => 'hand', 'title' => 'Implantação Acompanhada', 'description' => 'Implementação do painel com apoio personalizado da equipe Elo 42.', 'price' => 'Em breve', 'badge' => '', 'badge_type' => '', 'cta' => 'Falar com comercial', 'url' => $wa('Implantação Acompanhada')],
            ['icon' => 'diagnostic', 'title' => 'Diagnóstico Organizacional', 'description' => 'Análise completa da operação com recomendações práticas e plano de ação.', 'price' => 'R$ 497,00', 'badge' => '', 'badge_type' => '', 'cta' => 'Falar com comercial', 'url' => $wa('Diagnóstico Organizacional')],
            ['icon' => 'calendar', 'title' => 'Workshop: Gestão Eficiente para Igrejas', 'description' => 'Treinamento prático para líderes e equipes de gestão eclesiástica.', 'price' => 'Em breve', 'badge' => 'Em breve', 'badge_type' => 'coming', 'cta' => 'Em breve', 'url' => '#', 'is_disabled' => true],
        ];
    }

    private function buildCommercialWhatsAppUrl(string $serviceName): string
    {
        $user = \App\Core\Session::user() ?? [];
        $name = trim((string) ($user['name'] ?? ''));
        $greeting = $name !== '' ? "Olá, sou {$name}." : 'Olá!';
        $message = "{$greeting} Tenho interesse no serviço \"{$serviceName}\" e gostaria de mais informações.";

        return 'https://wa.me/5513978008047?text=' . rawurlencode($message);
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
                'url'         => $this->buildCommercialWhatsAppUrl('Painel de Gestão de Igrejas — Plano mensal'),
            ],
            [
                'product'     => 'Expositor IA',
                'package'     => 'Pacote de entrada',
                'price'       => 'Pacotes de créditos',
                'description' => 'Ideal para começar com esboços e estudos no ritmo da sua equipe, pagando por uso.',
                'cta'         => 'Comprar créditos',
                'url'         => url('/hub/creditos'),
            ],
            [
                'product'     => 'Google Ad Grants + Tráfego',
                'package'     => 'Implantação completa',
                'price'       => 'A partir de R$ 497,00',
                'description' => 'Aprovação, estrutura de campanhas e acompanhamento de performance.',
                'cta'         => 'Quero este pacote',
                'url'         => $this->buildCommercialWhatsAppUrl('Google Ad Grants + Tráfego — Implantação completa'),
            ],
            [
                'product'     => 'Pacote Operacional para ONGs',
                'package'     => 'Google para ONGs + TechSoup + Microsoft/Canva/Slack',
                'price'       => 'Sob consulta',
                'description' => 'Conjunto de benefícios para reduzir custo e aumentar produtividade.',
                'cta'         => 'Solicitar proposta',
                'url'         => $this->buildCommercialWhatsAppUrl('Pacote Operacional para ONGs'),
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

        $teamMembers = [];
        $availableRoles = [];
        $degraded = false;

        $currentUserId = (int) ($context['user']['id'] ?? 0);
        if ($currentUserId > 0) {
            \App\Models\Organization::ensureOwnerLink((int) $organization['id'], $currentUserId);
        }

        try {
            $teamMembers = \App\Models\Organization::getUsers((int) $organization['id']) ?: [];
        } catch (\Throwable $e) {
            error_log('[Hub.usuarios] team load failed: ' . $e->getMessage());
            $degraded = true;
        }

        try {
            $pdo = \App\Core\Database::connection();
            $stmt = $pdo->prepare("SELECT id, name FROM roles WHERE slug LIKE 'org-%' ORDER BY name ASC");
            $stmt->execute();
            $availableRoles = $stmt->fetchAll() ?: [];
        } catch (\Throwable $e) {
            error_log('[Hub.usuarios] roles load failed: ' . $e->getMessage());
            $degraded = true;
        }

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
