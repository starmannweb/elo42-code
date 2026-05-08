<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Database;

class AdminBlogController extends Controller
{
    public function index(Request $request): void
    {
        $search = $request->input('search', '');
        $status = $request->input('status', '');
        $page = (int) ($request->input('page', '1'));
        $perPage = 20;
        $articles = [];
        $total = 0;

        try {
            $pdo = Database::connection();
            $where = '1=1';
            $params = [];
            if ($search) {
                $where .= ' AND (title LIKE :s OR author LIKE :s)';
                $params['s'] = "%{$search}%";
            }
            if ($status) {
                $where .= ' AND status = :st';
                $params['st'] = $status;
            }

            $countStmt = $pdo->prepare("SELECT COUNT(*) FROM blog_articles WHERE {$where}");
            $countStmt->execute($params);
            $total = (int) $countStmt->fetchColumn();

            $offset = ($page - 1) * $perPage;
            $stmt = $pdo->prepare("SELECT * FROM blog_articles WHERE {$where} ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}");
            $stmt->execute($params);
            $articles = $stmt->fetchAll();
        } catch (\Throwable $e) {
            error_log('[ADMIN_BLOG] ' . $e->getMessage());
        }

        $this->view('admin/blog/index', [
            'pageTitle'  => 'Blog — Admin',
            'breadcrumb' => 'Blog',
            'articles'   => $articles,
            'pagination' => ['total' => $total, 'page' => $page, 'perPage' => $perPage, 'totalPages' => (int) ceil(max(1, $total) / $perPage)],
            'filters'    => ['search' => $search, 'status' => $status],
        ]);
    }

    public function create(Request $request): void
    {
        $this->view('admin/blog/form', [
            'pageTitle'  => 'Novo artigo — Admin',
            'breadcrumb' => 'Novo artigo',
            'item'       => null,
        ]);
    }

    public function store(Request $request): void
    {
        $data = $this->validateAndSanitize($request);
        if (isset($data['_error'])) {
            Session::flash('error', $data['_error']);
            redirect('/admin/blog/novo');
            return;
        }

        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("INSERT INTO blog_articles (title, slug, summary, content, cover_image, author, status, published_at, meta_title, meta_description, focus_keyword, noindex) VALUES (:title, :slug, :summary, :content, :cover_image, :author, :status, :published_at, :meta_title, :meta_description, :focus_keyword, :noindex)");
            $stmt->execute($data);
            Session::flash('success', 'Artigo criado com sucesso.');
        } catch (\Throwable $e) {
            error_log('[ADMIN_BLOG] ' . $e->getMessage());
            Session::flash('error', 'Erro ao criar artigo.');
        }

        redirect('/admin/blog');
    }

    public function edit(Request $request): void
    {
        $id = (int) $request->param('id', 0);
        $item = $this->findArticle($id);
        if (!$item) {
            Session::flash('error', 'Artigo não encontrado.');
            redirect('/admin/blog');
            return;
        }

        $this->view('admin/blog/form', [
            'pageTitle'  => 'Editar artigo — Admin',
            'breadcrumb' => 'Editar artigo',
            'item'       => $item,
        ]);
    }

    public function update(Request $request): void
    {
        $id = (int) $request->param('id', 0);
        $item = $this->findArticle($id);
        if (!$item) {
            Session::flash('error', 'Artigo não encontrado.');
            redirect('/admin/blog');
            return;
        }

        $data = $this->validateAndSanitize($request, $id);
        if (isset($data['_error'])) {
            Session::flash('error', $data['_error']);
            redirect('/admin/blog/' . $id . '/editar');
            return;
        }

        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("UPDATE blog_articles SET title=:title, slug=:slug, summary=:summary, content=:content, cover_image=:cover_image, author=:author, status=:status, published_at=:published_at, meta_title=:meta_title, meta_description=:meta_description, focus_keyword=:focus_keyword, noindex=:noindex WHERE id=:id");
            $stmt->execute($data + ['id' => $id]);
            Session::flash('success', 'Artigo atualizado.');
        } catch (\Throwable $e) {
            error_log('[ADMIN_BLOG] ' . $e->getMessage());
            Session::flash('error', 'Erro ao atualizar artigo.');
        }

        redirect('/admin/blog');
    }

    public function destroy(Request $request): void
    {
        $id = (int) $request->param('id', 0);
        try {
            $pdo = Database::connection();
            $pdo->prepare("DELETE FROM blog_articles WHERE id = :id")->execute(['id' => $id]);
            Session::flash('success', 'Artigo excluído.');
        } catch (\Throwable $e) {
            error_log('[ADMIN_BLOG] ' . $e->getMessage());
            Session::flash('error', 'Erro ao excluir artigo.');
        }

        redirect('/admin/blog');
    }

    private function findArticle(int $id): ?array
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("SELECT * FROM blog_articles WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();
            return $row ?: null;
        } catch (\Throwable) {
            return null;
        }
    }

    private function validateAndSanitize(Request $request, int $excludeId = 0): array
    {
        $title = trim((string) $request->input('title', ''));
        $slug = trim((string) $request->input('slug', ''));
        $summary = trim((string) $request->input('summary', ''));
        $content = trim((string) $request->input('content', ''));
        $coverImage = trim((string) $request->input('cover_image', ''));
        $author = trim((string) $request->input('author', 'Equipe Elo 42')) ?: 'Equipe Elo 42';
        $status = $request->input('status', 'draft') === 'published' ? 'published' : 'draft';
        $publishedAt = null;

        if (!$title) {
            return ['_error' => 'O título é obrigatório.'];
        }
        if (!$slug) {
            $slug = $this->slugify($title);
        }
        if ($status === 'published') {
            $publishedAt = date('Y-m-d H:i:s');
        }

        $metaTitle = trim((string) $request->input('meta_title', '')) ?: null;
        $metaDescription = trim((string) $request->input('meta_description', '')) ?: null;
        $focusKeyword = trim((string) $request->input('focus_keyword', '')) ?: null;
        $noindex = $request->input('noindex', '0') === '1' ? 1 : 0;

        return [
            'title'            => $title,
            'slug'             => $slug,
            'summary'          => $summary,
            'content'          => $content,
            'cover_image'      => $coverImage,
            'author'           => $author,
            'status'           => $status,
            'published_at'     => $publishedAt,
            'meta_title'       => $metaTitle,
            'meta_description' => $metaDescription,
            'focus_keyword'    => $focusKeyword,
            'noindex'          => $noindex,
        ];
    }

    private function slugify(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        $text = str_replace(['á','à','ã','â','ä'], 'a', $text);
        $text = str_replace(['é','è','ê','ë'], 'e', $text);
        $text = str_replace(['í','ì','î','ï'], 'i', $text);
        $text = str_replace(['ó','ò','õ','ô','ö'], 'o', $text);
        $text = str_replace(['ú','ù','û','ü'], 'u', $text);
        $text = str_replace(['ç'], 'c', $text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text) ?? $text;
        $text = preg_replace('/[\s-]+/', '-', $text) ?? $text;
        return trim($text, '-');
    }
}
