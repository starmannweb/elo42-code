<?php

declare(strict_types=1);

namespace Modules\Portal\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class MemberPortalController extends Controller
{
    private ?\PDO $pdo = null;
    private array $tableColumns = [];
    private array $tableExists = [];

    private function buildBaseContext(string $breadcrumb, string $activeMenu): array
    {
        $user = Session::user() ?? [];
        $organization = Session::get('organization') ?? [];
        $firstName = explode(' ', trim((string) ($user['name'] ?? 'Usuário')))[0] ?? 'Usuário';
        $greeting = match (true) {
            (int) date('H') < 12 => 'Bom dia',
            (int) date('H') < 18 => 'Boa tarde',
            default => 'Boa noite',
        };

        return [
            'user' => $user,
            'organization' => $organization,
            'member' => $this->currentMember(),
            'firstName' => $firstName,
            'greeting' => $greeting,
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'appearanceSettings' => $this->organizationSettings([
                'appearance_primary',
                'appearance_accent',
                'appearance_background',
                'appearance_text',
                'theme_color',
                'pwa_short_name',
            ]),
        ];
    }

    public function demoAccess(Request $request): void
    {
        Session::set('user', [
            'id' => 9999,
            'name' => 'Membro de Demonstração',
            'email' => 'membro@demo.elo42.com',
            'phone' => '(11) 98765-4321',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        Session::set('organization', [
            'id' => 9999,
            'name' => 'Igreja de Demonstração',
            'slug' => 'igreja-demonstracao',
            'plan' => 'premium',
            'role_name' => 'Membro',
        ]);

        Session::flash('success', 'Acesso de demonstração criado com sucesso.');
        redirect('/membro');
    }

    public function index(Request $request): void
    {
        try {
            $context = $this->buildBaseContext('Início', 'inicio');
            $events = $this->upcomingEvents(5);
            $sermons = $this->publishedSermons(3);
            $plans = $this->readingPlansData(1);
            $achievements = $this->achievementData();

            $this->view('portal/dashboard', array_merge($context, [
                'pageTitle' => 'Início — Portal do Membro',
                'banner' => $this->featuredBanner(),
                'quickActions' => $this->quickActions(),
                'upcomingEvents' => $events,
                'featuredSermons' => $sermons,
                'currentPlan' => $plans[0] ?? $this->fallbackReadingPlans()[0],
                'verseOfDay' => $this->verseOfDay(),
                'achievementSummary' => [
                    'earned' => count(array_filter($achievements, static fn (array $item): bool => !empty($item['earned']))),
                    'total' => count($achievements),
                    'points' => array_sum(array_map(static fn (array $item): int => !empty($item['earned']) ? (int) ($item['points'] ?? 0) : 0, $achievements)),
                ],
            ]));
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function bible(Request $request): void
    {
        try {
            $context = $this->buildBaseContext('Bíblia', 'biblia');
            $books = $this->bibleBooks();
            $selectedVersion = (string) $request->input('version', 'ARA');
            $selectedBook = (string) $request->input('book', 'João');
            if (!isset($books[$selectedBook])) {
                $selectedBook = 'João';
            }
            $chapterCount = (int) $books[$selectedBook];
            $selectedChapter = max(1, min($chapterCount, (int) $request->input('chapter', '3')));

            $this->view('portal/bible', array_merge($context, [
                'pageTitle' => 'Bíblia — Portal do Membro',
                'versions' => ['ARA', 'ARC', 'ACF', 'NAA', 'NVI', 'NTLH', 'KJA', 'A21', 'NVT', 'NBV-P', 'VFL', 'TB'],
                'books' => $books,
                'selectedVersion' => $selectedVersion,
                'selectedBook' => $selectedBook,
                'selectedChapter' => $selectedChapter,
                'chapterCount' => $chapterCount,
                'passage' => $this->biblePassage($selectedBook, $selectedChapter),
            ]));
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function readingPlans(Request $request): void
    {
        try {
            $context = $this->buildBaseContext('Planos de leitura', 'planos-leitura');

            $this->view('portal/reading-plans', array_merge($context, [
                'pageTitle' => 'Planos de leitura — Portal do Membro',
                'plans' => $this->readingPlansData(12),
            ]));
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function ministrations(Request $request): void
    {
        try {
            $context = $this->buildBaseContext('Ministrações', 'ministracoes');
            $categories = $this->sermonCategories();
            $selectedCategory = trim((string) $request->input('category', 'Todas'));
            if (!in_array($selectedCategory, $categories, true)) {
                $selectedCategory = 'Todas';
            }

            $this->view('portal/ministrations', array_merge($context, [
                'pageTitle' => 'Ministrações — Portal do Membro',
                'sermons' => $this->filterSermonsByCategory($this->publishedSermons(12), $selectedCategory),
                'categories' => $categories,
                'selectedCategory' => $selectedCategory,
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
                'courses' => $this->courseData(),
            ]));
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function events(Request $request): void
    {
        try {
            $context = $this->buildBaseContext('Eventos', 'eventos');
            $categories = $this->eventCategories();
            $selectedDay = trim((string) $request->input('day', ''));
            $selectedCategory = trim((string) $request->input('category', 'Todos'));

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDay)) {
                $selectedDay = '';
            }

            if (!in_array($selectedCategory, $categories, true)) {
                $selectedCategory = 'Todos';
            }

            $this->view('portal/events', array_merge($context, [
                'pageTitle' => 'Eventos — Portal do Membro',
                'events' => $this->filterEvents($this->upcomingEvents(30), $selectedDay, $selectedCategory),
                'days' => $this->calendarStrip($selectedDay !== '' ? $selectedDay : date('Y-m-d')),
                'categories' => $categories,
                'selectedDay' => $selectedDay,
                'selectedCategory' => $selectedCategory,
            ]));
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function requests(Request $request): void
    {
        try {
            $context = $this->buildBaseContext('Solicitações', 'solicitacoes');

            $this->view('portal/requests', array_merge($context, [
                'pageTitle' => 'Solicitações — Portal do Membro',
                'requestTypes' => $this->requestTypes(),
                'requests' => $this->memberRequests(),
            ]));
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function storeRequest(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $type = (string) $request->input('type', 'general');
            $title = trim((string) $request->input('title', ''));
            $description = trim((string) $request->input('description', ''));
            $priority = (string) $request->input('priority', 'normal');
            $allowedPriorities = ['low', 'normal', 'high', 'urgent'];

            if ($orgId <= 0 || $description === '') {
                Session::flash('error', 'Informe os detalhes da solicitação.');
                redirect('/membro/solicitacoes');
            }

            $types = $this->requestTypes();
            $selectedType = $types[$type] ?? $types['general'];
            $databaseType = $selectedType['database_type'] ?? 'general';

            $pdo = $this->connection();
            if (!$pdo || !$this->tableExists('requests')) {
                Session::flash('error', 'A central de solicitações ainda não está disponível.');
                redirect('/membro/solicitacoes');
            }

            $stmt = $pdo->prepare(
                'INSERT INTO requests (organization_id, member_id, title, description, type, priority, status, created_by, created_at, updated_at)
                 VALUES (:organization_id, :member_id, :title, :description, :type, :priority, :status, :created_by, :created_at, :updated_at)'
            );
            $stmt->execute([
                'organization_id' => $orgId,
                'member_id' => $this->memberId() ?: null,
                'title' => $title !== '' ? $title : $selectedType['title'],
                'description' => $description,
                'type' => $databaseType,
                'priority' => in_array($priority, $allowedPriorities, true) ? $priority : 'normal',
                'status' => 'open',
                'created_by' => (int) (Session::user()['id'] ?? 0) ?: null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            Session::flash('success', 'Solicitação enviada para a equipe da igreja.');
            redirect('/membro/solicitacoes');
        } catch (\Throwable $e) {
            Session::flash('error', 'Não foi possível enviar a solicitação: ' . $e->getMessage());
            redirect('/membro/solicitacoes');
        }
    }

    public function achievements(Request $request): void
    {
        try {
            $context = $this->buildBaseContext('Conquistas', 'conquistas');

            $this->view('portal/achievements', array_merge($context, [
                'pageTitle' => 'Conquistas — Portal do Membro',
                'achievements' => $this->achievementData(),
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
                'pageTitle' => 'Ofertas — Portal do Membro',
                'campaigns' => $this->campaignData(),
                'pix' => $this->pixSettings(),
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

    public function saveSettings(Request $request): void
    {
        try {
            Session::flash('success', 'Preferências salvas com sucesso.');
            redirect('/membro/configuracoes');
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    private function quickActions(): array
    {
        return [
            ['label' => 'Bíblia', 'href' => '/membro/biblia', 'icon' => 'book'],
            ['label' => 'Eventos', 'href' => '/membro/eventos', 'icon' => 'calendar'],
            ['label' => 'Ministrações', 'href' => '/membro/ministracoes', 'icon' => 'audio'],
            ['label' => 'Ofertas', 'href' => '/membro/ofertas', 'icon' => 'gift'],
            ['label' => 'Solicitações', 'href' => '/membro/solicitacoes', 'icon' => 'message'],
            ['label' => 'Planos', 'href' => '/membro/planos-leitura', 'icon' => 'check'],
            ['label' => 'Cursos', 'href' => '/membro/cursos', 'icon' => 'course'],
            ['label' => 'Conquistas', 'href' => '/membro/conquistas', 'icon' => 'award'],
        ];
    }

    private function featuredBanner(): array
    {
        $fallback = [
            'title' => 'Acompanhe tudo o que acontece na sua igreja',
            'description' => 'Eventos, cursos, ministrações, campanhas e solicitações reunidos em um só lugar.',
            'image_url' => '',
            'link_url' => '/membro/eventos',
            'label' => 'Portal do membro',
        ];

        if (!$this->tableExists('banners')) {
            return $fallback;
        }

        $order = in_array('display_order', $this->columns('banners'), true)
            ? 'display_order ASC'
            : (in_array('sort_order', $this->columns('banners'), true) ? 'sort_order ASC' : 'created_at DESC');

        $rows = $this->fetchAll(
            "SELECT * FROM banners
             WHERE organization_id = :org AND status = 'active'
             ORDER BY {$order}, created_at DESC
             LIMIT 1",
            ['org' => $this->orgId()]
        );

        if (empty($rows)) {
            return $fallback;
        }

        $banner = $rows[0];
        return [
            'title' => (string) ($banner['title'] ?? $fallback['title']),
            'description' => (string) ($banner['description'] ?? $fallback['description']),
            'image_url' => (string) ($banner['image_url'] ?? ''),
            'link_url' => (string) ($banner['link_url'] ?? '/membro/eventos'),
            'label' => 'Destaque',
        ];
    }

    private function upcomingEvents(int $limit): array
    {
        if (!$this->tableExists('events')) {
            return $this->fallbackEvents();
        }

        $events = $this->fetchAll(
            "SELECT *
             FROM events
             WHERE organization_id = :org
               AND status IN ('published', 'ongoing')
               AND (start_date IS NULL OR start_date >= :today)
             ORDER BY start_date ASC
             LIMIT {$limit}",
            ['org' => $this->orgId(), 'today' => date('Y-m-d 00:00:00')]
        );

        return empty($events) ? $this->fallbackEvents() : array_map([$this, 'normalizeEvent'], $events);
    }

    private function normalizeEvent(array $event): array
    {
        $title = (string) ($event['title'] ?? 'Evento');
        return [
            'id' => (int) ($event['id'] ?? 0),
            'title' => $title,
            'description' => (string) ($event['description'] ?? ''),
            'location' => (string) ($event['location'] ?? 'Local a confirmar'),
            'start_date' => (string) ($event['start_date'] ?? ''),
            'end_date' => (string) ($event['end_date'] ?? ''),
            'status' => (string) ($event['status'] ?? 'published'),
            'category' => $this->eventCategoryFromTitle($title),
        ];
    }

    private function publishedSermons(int $limit): array
    {
        if (!$this->tableExists('sermons')) {
            return $this->fallbackSermons();
        }

        $sermons = $this->fetchAll(
            "SELECT *
             FROM sermons
             WHERE organization_id = :org AND status = 'published'
             ORDER BY sermon_date DESC, created_at DESC
             LIMIT {$limit}",
            ['org' => $this->orgId()]
        );

        return empty($sermons) ? $this->fallbackSermons() : array_map(static function (array $sermon): array {
            return [
                'id' => (int) ($sermon['id'] ?? 0),
                'title' => (string) ($sermon['title'] ?? 'Ministração'),
                'preacher' => (string) ($sermon['preacher'] ?? 'Equipe pastoral'),
                'date' => (string) ($sermon['sermon_date'] ?? ''),
                'reference' => (string) ($sermon['bible_reference'] ?? ''),
                'summary' => (string) ($sermon['summary'] ?? ''),
                'series' => (string) ($sermon['series_name'] ?? ''),
                'tags' => (string) ($sermon['tags'] ?? ''),
            ];
        }, $sermons);
    }

    private function sermonCategories(): array
    {
        return ['Todas', 'Fé', 'Família', 'Discipulado', 'Evangelho'];
    }

    private function filterSermonsByCategory(array $sermons, string $category): array
    {
        if ($category === 'Todas') {
            return $sermons;
        }

        $needle = $this->normalizeSearchText($category);

        return array_values(array_filter($sermons, function (array $sermon) use ($needle): bool {
            $haystack = implode(' ', [
                (string) ($sermon['title'] ?? ''),
                (string) ($sermon['summary'] ?? ''),
                (string) ($sermon['series'] ?? ''),
                (string) ($sermon['tags'] ?? ''),
            ]);

            return str_contains($this->normalizeSearchText($haystack), $needle);
        }));
    }

    private function filterEvents(array $events, string $day, string $category): array
    {
        return array_values(array_filter($events, function (array $event) use ($day, $category): bool {
            if ($day !== '') {
                $eventDate = (string) ($event['start_date'] ?? '');
                if ($eventDate === '' || date('Y-m-d', strtotime($eventDate)) !== $day) {
                    return false;
                }
            }

            if ($category !== 'Todos' && (string) ($event['category'] ?? '') !== $category) {
                return false;
            }

            return true;
        }));
    }

    private function normalizeSearchText(string $value): string
    {
        $value = function_exists('mb_strtolower') ? mb_strtolower($value) : strtolower($value);
        if (!function_exists('iconv')) {
            return $value;
        }

        $converted = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);

        return is_string($converted) ? $converted : $value;
    }

    private function readingPlansData(int $limit): array
    {
        if (!$this->tableExists('reading_plans')) {
            return $this->fallbackReadingPlans();
        }

        $plans = $this->fetchAll(
            "SELECT *
             FROM reading_plans
             WHERE organization_id = :org AND status = 'active'
             ORDER BY created_at DESC
             LIMIT {$limit}",
            ['org' => $this->orgId()]
        );

        if (empty($plans)) {
            return $this->fallbackReadingPlans();
        }

        return array_map(static function (array $plan): array {
            $duration = (int) ($plan['duration_days'] ?? 30);
            $progress = min(100, max(0, (int) ($plan['progress'] ?? 0)));

            return [
                'id' => (int) ($plan['id'] ?? 0),
                'title' => (string) ($plan['title'] ?? 'Plano de leitura'),
                'description' => (string) ($plan['description'] ?? ''),
                'duration_days' => $duration,
                'book_range' => (string) ($plan['book_range'] ?? 'Bíblia'),
                'status' => (string) ($plan['status'] ?? 'active'),
                'progress' => $progress,
                'current_day' => max(1, (int) ceil(($duration * $progress) / 100)),
            ];
        }, $plans);
    }

    private function courseData(): array
    {
        if (!$this->tableExists('courses')) {
            return $this->fallbackCourses();
        }

        $courses = $this->fetchAll(
            "SELECT *
             FROM courses
             WHERE organization_id = :org AND status IN ('published', 'ongoing', 'completed')
             ORDER BY FIELD(status, 'ongoing', 'published', 'completed'), created_at DESC
             LIMIT 24",
            ['org' => $this->orgId()]
        );

        if (empty($courses)) {
            return $this->fallbackCourses();
        }

        return array_map(static function (array $course): array {
            return [
                'id' => (int) ($course['id'] ?? 0),
                'title' => (string) ($course['title'] ?? 'Curso'),
                'description' => (string) ($course['description'] ?? ''),
                'instructor' => (string) ($course['instructor'] ?? 'Equipe ministerial'),
                'duration_hours' => (int) ($course['duration_hours'] ?? 0),
                'start_date' => (string) ($course['start_date'] ?? ''),
                'end_date' => (string) ($course['end_date'] ?? ''),
                'status' => (string) ($course['status'] ?? 'published'),
                'pdf_file_url' => (string) ($course['pdf_file_url'] ?? ''),
                'video_url' => (string) ($course['video_url'] ?? ''),
                'progress' => (int) ($course['progress'] ?? 0),
            ];
        }, $courses);
    }

    private function memberRequests(): array
    {
        if (!$this->tableExists('requests')) {
            return [];
        }

        $memberId = $this->memberId();
        $params = ['org' => $this->orgId()];
        $sql = "SELECT * FROM requests WHERE organization_id = :org AND title <> 'Teste UX'";

        if ($memberId > 0) {
            $sql .= " AND member_id = :member_id";
            $params['member_id'] = $memberId;
        }

        $sql .= " ORDER BY created_at DESC LIMIT 8";

        return $this->fetchAll($sql, $params);
    }

    private function achievementData(): array
    {
        $fallback = $this->fallbackAchievements();
        if (!$this->tableExists('achievements')) {
            return $fallback;
        }

        $achievements = $this->fetchAll(
            "SELECT * FROM achievements WHERE organization_id = :org AND status = 'active' ORDER BY points ASC, created_at DESC LIMIT 24",
            ['org' => $this->orgId()]
        );

        if (empty($achievements)) {
            return $fallback;
        }

        $earned = [];
        $memberId = $this->memberId();
        if ($memberId > 0 && $this->tableExists('member_achievements')) {
            $rows = $this->fetchAll('SELECT achievement_id FROM member_achievements WHERE member_id = :member_id', ['member_id' => $memberId]);
            $earned = array_map(static fn (array $row): int => (int) ($row['achievement_id'] ?? 0), $rows);
        }

        return array_map(static function (array $achievement) use ($earned): array {
            $id = (int) ($achievement['id'] ?? 0);
            $points = (int) ($achievement['points'] ?? 10);
            $isEarned = in_array($id, $earned, true);

            return [
                'id' => $id,
                'title' => (string) ($achievement['title'] ?? 'Conquista'),
                'description' => (string) ($achievement['description'] ?? ''),
                'icon' => (string) ($achievement['icon'] ?? 'award'),
                'points' => $points,
                'criteria_type' => (string) ($achievement['criteria_type'] ?? 'growth'),
                'earned' => $isEarned,
                'progress' => $isEarned ? 100 : min(85, max(10, $points * 5)),
            ];
        }, $achievements);
    }

    private function campaignData(): array
    {
        if (!$this->tableExists('campaigns')) {
            return $this->fallbackCampaigns();
        }

        $campaigns = $this->fetchAll(
            "SELECT *
             FROM campaigns
             WHERE organization_id = :org AND status IN ('active', 'published', 'completed')
             ORDER BY created_at DESC
             LIMIT 12",
            ['org' => $this->orgId()]
        );

        if (empty($campaigns)) {
            return $this->fallbackCampaigns();
        }

        return array_map(static function (array $campaign): array {
            $goal = (float) ($campaign['goal_amount'] ?? 0);
            $raised = (float) ($campaign['raised_amount'] ?? 0);
            return [
                'id' => (int) ($campaign['id'] ?? 0),
                'title' => (string) ($campaign['title'] ?? 'Campanha'),
                'description' => (string) ($campaign['description'] ?? ''),
                'goal_amount' => $goal,
                'raised_amount' => $raised,
                'end_date' => (string) ($campaign['end_date'] ?? ''),
                'status' => (string) ($campaign['status'] ?? 'active'),
                'designation' => (string) ($campaign['designation'] ?? 'Campanha da igreja'),
                'progress' => $goal > 0 ? min(100, (int) round(($raised / $goal) * 100)) : 0,
            ];
        }, $campaigns);
    }

    private function pixSettings(): array
    {
        $org = Session::get('organization') ?? [];
        $settings = [
            'pix_key' => '',
            'pix_type' => 'PIX',
            'pix_name' => (string) ($org['name'] ?? 'Igreja'),
            'pix_instruction' => 'Escolha uma campanha, copie a chave PIX e envie sua contribuição pelo aplicativo do seu banco.',
        ];

        if (!$this->tableExists('settings')) {
            return $settings;
        }

        $rows = $this->fetchAll(
            "SELECT `key`, value FROM settings WHERE organization_id = :org AND `key` IN ('pix_key', 'pix_type', 'pix_name', 'pix_beneficiary', 'pix_instruction')",
            ['org' => $this->orgId()]
        );

        foreach ($rows as $row) {
            $key = (string) ($row['key'] ?? '');
            if ($key === 'pix_beneficiary') {
                $settings['pix_name'] = (string) ($row['value'] ?? $settings['pix_name']);
                continue;
            }
            if (array_key_exists($key, $settings)) {
                $settings[$key] = (string) ($row['value'] ?? $settings[$key]);
            }
        }

        return $settings;
    }

    private function organizationSettings(array $keys): array
    {
        if (empty($keys) || !$this->tableExists('settings')) {
            return [];
        }

        $placeholders = [];
        $params = ['org' => $this->orgId()];
        foreach (array_values($keys) as $index => $key) {
            $param = 'key_' . $index;
            $placeholders[] = ':' . $param;
            $params[$param] = (string) $key;
        }

        $rows = $this->fetchAll(
            'SELECT `key`, value FROM settings WHERE organization_id = :org AND `key` IN (' . implode(',', $placeholders) . ')',
            $params
        );

        $settings = [];
        foreach ($rows as $row) {
            $settings[(string) ($row['key'] ?? '')] = (string) ($row['value'] ?? '');
        }

        return $settings;
    }

    private function requestTypes(): array
    {
        return [
            'prayer' => [
                'title' => 'Pedido de oração',
                'subtitle' => 'Compartilhe um motivo de oração com a equipe pastoral.',
                'database_type' => 'prayer',
                'tone' => 'danger',
            ],
            'baptism' => [
                'title' => 'Batismo',
                'subtitle' => 'Demonstre interesse no próximo batismo.',
                'database_type' => 'support',
                'tone' => 'primary',
            ],
            'material' => [
                'title' => 'Cesta básica',
                'subtitle' => 'Solicite apoio material da igreja.',
                'database_type' => 'material',
                'tone' => 'warning',
            ],
            'visit' => [
                'title' => 'Visita pastoral',
                'subtitle' => 'Peça uma visita ou acompanhamento próximo.',
                'database_type' => 'support',
                'tone' => 'neutral',
            ],
            'pastoral' => [
                'title' => 'Direção pastoral',
                'subtitle' => 'Agende uma conversa com a liderança.',
                'database_type' => 'support',
                'tone' => 'success',
            ],
            'general' => [
                'title' => 'Outro pedido',
                'subtitle' => 'Envie uma solicitação geral para a equipe.',
                'database_type' => 'general',
                'tone' => 'neutral',
            ],
        ];
    }

    private function calendarStrip(string $activeDate = ''): array
    {
        $days = [];
        $start = new \DateTimeImmutable('today');
        $weekdays = ['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SÁB'];
        $months = ['JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ'];
        $activeDate = $activeDate !== '' ? $activeDate : $start->format('Y-m-d');

        for ($i = 0; $i < 14; $i++) {
            $date = $start->modify("+{$i} days");
            $days[] = [
                'iso' => $date->format('Y-m-d'),
                'day' => $date->format('d'),
                'weekday' => $i === 0 ? 'HOJE' : $weekdays[(int) $date->format('w')],
                'month' => $months[((int) $date->format('n')) - 1],
                'active' => $date->format('Y-m-d') === $activeDate,
            ];
        }

        return $days;
    }

    private function eventCategories(): array
    {
        return ['Todos', 'Culto', 'Conferência', 'Estudo bíblico', 'Jovens', 'Louvor', 'Especial'];
    }

    private function eventCategoryFromTitle(string $title): string
    {
        $normalized = function_exists('mb_strtolower') ? mb_strtolower($title) : strtolower($title);
        return match (true) {
            str_contains($normalized, 'confer') => 'Conferência',
            str_contains($normalized, 'estudo') => 'Estudo bíblico',
            str_contains($normalized, 'jovem') => 'Jovens',
            str_contains($normalized, 'louvor') => 'Louvor',
            str_contains($normalized, 'culto') => 'Culto',
            default => 'Especial',
        };
    }

    private function verseOfDay(): array
    {
        return [
            'reference' => 'Provérbios 3:5-6',
            'text' => 'Confie no Senhor de todo o seu coração e não se apoie em seu próprio entendimento; reconheça o Senhor em todos os seus caminhos, e ele endireitará as suas veredas.',
            'tag' => 'Sabedoria',
        ];
    }

    private function bibleBooks(): array
    {
        return [
            'Gênesis' => 50, 'Êxodo' => 40, 'Levítico' => 27, 'Números' => 36, 'Deuteronômio' => 34,
            'Josué' => 24, 'Juízes' => 21, 'Rute' => 4, '1 Samuel' => 31, '2 Samuel' => 24,
            '1 Reis' => 22, '2 Reis' => 25, '1 Crônicas' => 29, '2 Crônicas' => 36, 'Esdras' => 10,
            'Neemias' => 13, 'Ester' => 10, 'Jó' => 42, 'Salmos' => 150, 'Provérbios' => 31,
            'Eclesiastes' => 12, 'Cânticos' => 8, 'Isaías' => 66, 'Jeremias' => 52, 'Lamentações' => 5,
            'Ezequiel' => 48, 'Daniel' => 12, 'Oseias' => 14, 'Joel' => 3, 'Amós' => 9,
            'Obadias' => 1, 'Jonas' => 4, 'Miqueias' => 7, 'Naum' => 3, 'Habacuque' => 3,
            'Sofonias' => 3, 'Ageu' => 2, 'Zacarias' => 14, 'Malaquias' => 4,
            'Mateus' => 28, 'Marcos' => 16, 'Lucas' => 24, 'João' => 21, 'Atos' => 28,
            'Romanos' => 16, '1 Coríntios' => 16, '2 Coríntios' => 13, 'Gálatas' => 6, 'Efésios' => 6,
            'Filipenses' => 4, 'Colossenses' => 4, '1 Tessalonicenses' => 5, '2 Tessalonicenses' => 3,
            '1 Timóteo' => 6, '2 Timóteo' => 4, 'Tito' => 3, 'Filemom' => 1, 'Hebreus' => 13,
            'Tiago' => 5, '1 Pedro' => 5, '2 Pedro' => 3, '1 João' => 5, '2 João' => 1,
            '3 João' => 1, 'Judas' => 1, 'Apocalipse' => 22,
        ];
    }

    private function biblePassage(string $book, int $chapter): array
    {
        if ($book === 'João' && $chapter === 3) {
            return $this->samplePassage();
        }

        return [[
            'number' => 1,
            'text' => 'Leitura selecionada: ' . $book . ' ' . $chapter . '. A navegação agora contempla todos os 66 livros e capítulos; o texto completo deve ser conectado a uma base bíblica licenciada da tradução escolhida.',
        ]];
    }

    private function samplePassage(): array
    {
        return [
            ['number' => 16, 'text' => 'Porque Deus tanto amou o mundo que deu o seu Filho unigênito, para que todo o que nele crer não pereça, mas tenha a vida eterna.'],
            ['number' => 17, 'text' => 'Pois Deus enviou o seu Filho ao mundo, não para condenar o mundo, mas para que este fosse salvo por meio dele.'],
            ['number' => 18, 'text' => 'Quem nele crê não é condenado, mas quem não crê já está condenado por não crer no nome do Filho unigênito de Deus.'],
        ];
    }

    private function fallbackEvents(): array
    {
        return [
            [
                'id' => 0,
                'title' => 'Culto de celebração',
                'description' => 'Encontro semanal com louvor, palavra e comunhão.',
                'location' => 'Auditório principal',
                'start_date' => date('Y-m-d 19:30:00', strtotime('next sunday')),
                'end_date' => '',
                'status' => 'published',
                'category' => 'Culto',
            ],
            [
                'id' => 0,
                'title' => 'Estudo bíblico',
                'description' => 'Tempo de aprofundamento nas Escrituras.',
                'location' => 'Sala de ensino',
                'start_date' => date('Y-m-d 20:00:00', strtotime('next wednesday')),
                'end_date' => '',
                'status' => 'published',
                'category' => 'Estudo bíblico',
            ],
        ];
    }

    private function fallbackSermons(): array
    {
        return [
            [
                'id' => 0,
                'title' => 'O poder da fé',
                'preacher' => 'Equipe pastoral',
                'date' => date('Y-m-d'),
                'reference' => 'Hebreus 11:1',
                'summary' => 'Uma mensagem para fortalecer a confiança em Deus no cotidiano.',
                'series' => 'Vida cristã',
                'tags' => 'fé, discipulado',
            ],
            [
                'id' => 0,
                'title' => 'Família: projeto de Deus',
                'preacher' => 'Equipe pastoral',
                'date' => date('Y-m-d', strtotime('-7 days')),
                'reference' => 'Josué 24:15',
                'summary' => 'Princípios bíblicos para uma casa firmada em propósito.',
                'series' => 'Família',
                'tags' => 'família',
            ],
            [
                'id' => 0,
                'title' => 'Jesus, o caminho',
                'preacher' => 'Equipe pastoral',
                'date' => date('Y-m-d', strtotime('-14 days')),
                'reference' => 'João 14:6',
                'summary' => 'A centralidade de Cristo na jornada de fé.',
                'series' => 'Evangelho',
                'tags' => 'salvação',
            ],
        ];
    }

    private function fallbackReadingPlans(): array
    {
        return [
            [
                'id' => 0,
                'title' => 'Salmos em 30 dias',
                'description' => 'Uma jornada devocional pelos salmos para fortalecer oração e confiança.',
                'duration_days' => 30,
                'book_range' => 'Salmos',
                'status' => 'active',
                'progress' => 12,
                'current_day' => 4,
            ],
            [
                'id' => 0,
                'title' => 'Evangelho de João',
                'description' => 'Leitura guiada sobre a vida e os sinais de Jesus.',
                'duration_days' => 21,
                'book_range' => 'João',
                'status' => 'active',
                'progress' => 0,
                'current_day' => 1,
            ],
        ];
    }

    private function fallbackCourses(): array
    {
        return [
            [
                'id' => 0,
                'title' => 'Fundamentos da fé cristã',
                'description' => 'Aulas introdutórias para quem deseja crescer com base bíblica.',
                'instructor' => 'Equipe ministerial',
                'duration_hours' => 6,
                'start_date' => '',
                'end_date' => '',
                'status' => 'published',
                'pdf_file_url' => '',
                'video_url' => '',
                'progress' => 0,
            ],
        ];
    }

    private function fallbackAchievements(): array
    {
        return [
            [
                'id' => 0,
                'title' => 'Primeiros passos',
                'description' => 'Complete seu perfil e comece a acompanhar a jornada.',
                'icon' => 'award',
                'points' => 10,
                'criteria_type' => 'profile',
                'earned' => false,
                'progress' => 45,
            ],
            [
                'id' => 0,
                'title' => 'Leitor constante',
                'description' => 'Avance em um plano de leitura por vários dias seguidos.',
                'icon' => 'book',
                'points' => 20,
                'criteria_type' => 'reading',
                'earned' => false,
                'progress' => 25,
            ],
            [
                'id' => 0,
                'title' => 'Participante ativo',
                'description' => 'Participe dos eventos e atividades da igreja.',
                'icon' => 'calendar',
                'points' => 30,
                'criteria_type' => 'events',
                'earned' => false,
                'progress' => 10,
            ],
        ];
    }

    private function fallbackCampaigns(): array
    {
        return [
            [
                'id' => 0,
                'title' => 'Dízimos e ofertas',
                'description' => 'Contribuições recorrentes para sustento da missão da igreja.',
                'goal_amount' => 0,
                'raised_amount' => 0,
                'end_date' => '',
                'status' => 'active',
                'designation' => 'Tesouraria geral',
                'progress' => 0,
            ],
            [
                'id' => 0,
                'title' => 'Campanha missionária',
                'description' => 'Apoio a projetos, ações sociais e expansão ministerial.',
                'goal_amount' => 10000,
                'raised_amount' => 0,
                'end_date' => '',
                'status' => 'active',
                'designation' => 'Missões',
                'progress' => 0,
            ],
        ];
    }

    private function currentMember(): array
    {
        $memberId = $this->memberId();
        if ($memberId <= 0 || !$this->tableExists('members')) {
            return [];
        }

        $rows = $this->fetchAll('SELECT * FROM members WHERE id = :id LIMIT 1', ['id' => $memberId]);
        return $rows[0] ?? [];
    }

    private function memberId(): int
    {
        $user = Session::user() ?? [];
        $orgId = $this->orgId();

        if (!empty($user['member_id'])) {
            return (int) $user['member_id'];
        }

        if ($orgId <= 0 || empty($user['email']) || !$this->tableExists('members')) {
            return 0;
        }

        $rows = $this->fetchAll(
            'SELECT id FROM members WHERE organization_id = :org AND email = :email LIMIT 1',
            ['org' => $orgId, 'email' => (string) $user['email']]
        );

        return (int) ($rows[0]['id'] ?? 0);
    }

    private function orgId(): int
    {
        $organization = Session::get('organization');
        return is_array($organization) ? (int) ($organization['id'] ?? 0) : 0;
    }

    private function connection(): ?\PDO
    {
        if ($this->pdo instanceof \PDO) {
            return $this->pdo;
        }

        try {
            $this->pdo = Database::connection();
        } catch (\Throwable $e) {
            $this->pdo = null;
        }

        return $this->pdo;
    }

    private function fetchAll(string $sql, array $params = []): array
    {
        $pdo = $this->connection();
        if (!$pdo) {
            return [];
        }

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll() ?: [];
        } catch (\Throwable $e) {
            error_log('[Portal] Query failed: ' . $e->getMessage());
            return [];
        }
    }

    private function tableExists(string $table): bool
    {
        if (array_key_exists($table, $this->tableExists)) {
            return $this->tableExists[$table];
        }

        $pdo = $this->connection();
        if (!$pdo || !preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
            return $this->tableExists[$table] = false;
        }

        try {
            $driver = (string) $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
            if ($driver === 'sqlite') {
                $stmt = $pdo->prepare("SELECT name FROM sqlite_master WHERE type = 'table' AND name = :table");
                $stmt->execute(['table' => $table]);
                return $this->tableExists[$table] = (bool) $stmt->fetchColumn();
            }

            if ($driver === 'pgsql') {
                $stmt = $pdo->prepare('SELECT 1 FROM information_schema.tables WHERE table_name = :table LIMIT 1');
                $stmt->execute(['table' => $table]);
                return $this->tableExists[$table] = (bool) $stmt->fetchColumn();
            }

            $stmt = $pdo->prepare('SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = :table LIMIT 1');
            $stmt->execute(['table' => $table]);
            return $this->tableExists[$table] = (bool) $stmt->fetchColumn();
        } catch (\Throwable $e) {
            return $this->tableExists[$table] = false;
        }
    }

    private function columns(string $table): array
    {
        if (array_key_exists($table, $this->tableColumns)) {
            return $this->tableColumns[$table];
        }

        $pdo = $this->connection();
        if (!$pdo || !$this->tableExists($table) || !preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
            return $this->tableColumns[$table] = [];
        }

        try {
            $driver = (string) $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
            if ($driver === 'sqlite') {
                $stmt = $pdo->query("PRAGMA table_info({$table})");
                return $this->tableColumns[$table] = array_map(static fn (array $row): string => (string) $row['name'], $stmt->fetchAll());
            }

            if ($driver === 'pgsql') {
                $stmt = $pdo->prepare('SELECT column_name FROM information_schema.columns WHERE table_name = :table');
                $stmt->execute(['table' => $table]);
                return $this->tableColumns[$table] = $stmt->fetchAll(\PDO::FETCH_COLUMN) ?: [];
            }

            $stmt = $pdo->query("SHOW COLUMNS FROM {$table}");
            return $this->tableColumns[$table] = array_map(static fn (array $row): string => (string) ($row['Field'] ?? ''), $stmt->fetchAll());
        } catch (\Throwable $e) {
            return $this->tableColumns[$table] = [];
        }
    }

    protected function handleError(\Throwable $e): void
    {
        Session::flash('error', 'Ocorreu um erro ao carregar a página: ' . $e->getMessage());
        redirect('/membro');
    }
}
