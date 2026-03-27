<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

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
