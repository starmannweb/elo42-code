<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Product extends Model
{
    protected static string $table = 'products';
    protected static array $fillable = ['category_id','name','slug','description','price','features','status','is_featured','sort_order'];

    public static function allWithCategory(): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->query("SELECT p.*, pc.name as category_name FROM products p LEFT JOIN product_categories pc ON p.category_id = pc.id ORDER BY p.sort_order, p.name");
        return $stmt->fetchAll();
    }

    public static function getCategories(): array
    {
        $pdo = Database::connection();
        return $pdo->query("SELECT * FROM product_categories ORDER BY sort_order, name")->fetchAll();
    }

    public static function createCategory(array $data): int|string
    {
        $pdo = Database::connection();
        $pdo->prepare("INSERT INTO product_categories (name, slug, description, sort_order) VALUES (:name, :slug, :desc, :sort)")
            ->execute(['name' => $data['name'], 'slug' => $data['slug'], 'desc' => $data['description'] ?? null, 'sort' => $data['sort_order'] ?? 0]);
        return $pdo->lastInsertId();
    }
}

class Service extends Model
{
    protected static string $table = 'services';
    protected static array $fillable = ['name','slug','description','rules','price','recurrence','status','sort_order'];
}

class Benefit extends Model
{
    protected static string $table = 'benefits';
    protected static array $fillable = ['name','slug','description','requirements','status','max_usage','valid_until'];

    public static function allWithUsageCount(): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->query("SELECT b.*, (SELECT COUNT(*) FROM benefit_usages bu WHERE bu.benefit_id = b.id) as usage_count FROM benefits b ORDER BY b.name");
        return $stmt->fetchAll();
    }
}

class Subscription extends Model
{
    protected static string $table = 'subscriptions';
    protected static array $fillable = ['organization_id','plan_name','plan_slug','price','billing_cycle','status','trial_ends_at','starts_at','expires_at','cancelled_at'];

    public static function allWithOrg(array $filters = []): array
    {
        $pdo = Database::connection();
        $where = '1=1';
        $params = [];
        if (!empty($filters['status'])) { $where .= " AND s.status = :st"; $params['st'] = $filters['status']; }
        $stmt = $pdo->prepare("SELECT s.*, o.name as org_name FROM subscriptions s JOIN organizations o ON s.organization_id = o.id WHERE {$where} ORDER BY s.created_at DESC");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getHistory(int $subId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT sh.*, u.name as user_name FROM subscription_history sh LEFT JOIN users u ON sh.created_by = u.id WHERE sh.subscription_id = :sid ORDER BY sh.created_at DESC");
        $stmt->execute(['sid' => $subId]);
        return $stmt->fetchAll();
    }

    public static function countByStatus(string $status): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM subscriptions WHERE status = :s");
        $stmt->execute(['s' => $status]);
        return (int) $stmt->fetchColumn();
    }
}

class Ticket extends Model
{
    protected static string $table = 'tickets';
    protected static array $fillable = ['user_id','organization_id','subject','description','category','priority','status','assigned_to','resolved_at','closed_at'];

    public static function allAdmin(array $filters = []): array
    {
        $pdo = Database::connection();
        $where = '1=1';
        $params = [];
        if (!empty($filters['status'])) { $where .= " AND t.status = :st"; $params['st'] = $filters['status']; }
        if (!empty($filters['priority'])) { $where .= " AND t.priority = :pr"; $params['pr'] = $filters['priority']; }
        $stmt = $pdo->prepare("SELECT t.*, u.name as user_name, u.email as user_email, o.name as org_name FROM tickets t JOIN users u ON t.user_id = u.id LEFT JOIN organizations o ON t.organization_id = o.id WHERE {$where} ORDER BY FIELD(t.priority,'urgent','high','normal','low'), t.created_at DESC");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getReplies(int $ticketId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT tr.*, u.name as user_name FROM ticket_replies tr JOIN users u ON tr.user_id = u.id WHERE tr.ticket_id = :tid ORDER BY tr.created_at ASC");
        $stmt->execute(['tid' => $ticketId]);
        return $stmt->fetchAll();
    }

    public static function countOpen(): int
    {
        $pdo = Database::connection();
        return (int) $pdo->query("SELECT COUNT(*) FROM tickets WHERE status IN ('open','in_progress')")->fetchColumn();
    }
}

class PlatformSetting extends Model
{
    protected static string $table = 'platform_settings';
    protected static array $fillable = ['setting_key','setting_value','setting_group','description','updated_by'];

    public static function get(string $key, ?string $default = null): ?string
    {
        $row = static::first('setting_key', $key);
        return $row ? $row['setting_value'] : $default;
    }

    public static function set(string $key, ?string $value, ?int $userId = null): void
    {
        $pdo = Database::connection();
        $pdo->prepare("UPDATE platform_settings SET setting_value = :val, updated_by = :uid WHERE setting_key = :key")
            ->execute(['val' => $value, 'uid' => $userId, 'key' => $key]);
    }

    public static function byGroup(?string $group = null): array
    {
        $pdo = Database::connection();
        if ($group) {
            $stmt = $pdo->prepare("SELECT * FROM platform_settings WHERE setting_group = :g ORDER BY setting_key");
            $stmt->execute(['g' => $group]);
        } else {
            $stmt = $pdo->query("SELECT * FROM platform_settings ORDER BY setting_group, setting_key");
        }
        return $stmt->fetchAll();
    }
}
