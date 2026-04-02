<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class FinancialTransaction extends Model
{
    protected static string $table = 'financial_transactions';
    protected static array $fillable = ['organization_id','category_id','type','description','amount','transaction_date','reference','member_id','status','notes','created_by'];

    public static function byOrg(int $orgId, array $filters = [], int $page = 1, int $perPage = 20): array
    {
        try {
            $pdo = Database::connection();
            $where = ['ft.organization_id = :org'];
            $params = ['org' => $orgId];

            if (!empty($filters['type'])) { $where[] = "ft.type = :type"; $params['type'] = $filters['type']; }
            if (!empty($filters['start_date'])) { $where[] = "ft.transaction_date >= :start"; $params['start'] = $filters['start_date']; }
            if (!empty($filters['end_date'])) { $where[] = "ft.transaction_date <= :end"; $params['end'] = $filters['end_date']; }

            $whereStr = implode(' AND ', $where);
            $offset = ($page - 1) * $perPage;

            $countStmt = $pdo->prepare("SELECT COUNT(*) FROM financial_transactions ft WHERE {$whereStr}");
            $countStmt->execute($params);
            $total = (int) $countStmt->fetchColumn();

            $stmt = $pdo->prepare("
                SELECT ft.*, fc.name as category_name, fc.color as category_color
                FROM financial_transactions ft
                LEFT JOIN financial_categories fc ON ft.category_id = fc.id
                WHERE {$whereStr}
                ORDER BY ft.transaction_date DESC, ft.id DESC
                LIMIT {$perPage} OFFSET {$offset}
            ");
            $stmt->execute($params);

            return ['data' => $stmt->fetchAll(), 'total' => $total, 'page' => $page, 'perPage' => $perPage, 'totalPages' => (int) ceil($total / $perPage), 'degraded' => false];
        } catch (\Throwable $e) {
            static::logModelFailure('byOrg', $e, ['organization_id' => $orgId]);
            return ['data' => [], 'total' => 0, 'page' => max(1, $page), 'perPage' => $perPage, 'totalPages' => 1, 'degraded' => true];
        }
    }

    public static function summary(int $orgId, ?string $startDate = null, ?string $endDate = null): array
    {
        try {
            $pdo = Database::connection();
            $where = "organization_id = :org";
            $params = ['org' => $orgId];
            if ($startDate) { $where .= " AND transaction_date >= :start"; $params['start'] = $startDate; }
            if ($endDate) { $where .= " AND transaction_date <= :end"; $params['end'] = $endDate; }

            $stmt = $pdo->prepare("
                SELECT type, SUM(amount) as total FROM financial_transactions
                WHERE {$where} AND status = 'confirmed' GROUP BY type
            ");
            $stmt->execute($params);
            $rows = $stmt->fetchAll();

            $income = 0; $expense = 0;
            foreach ($rows as $r) {
                if ($r['type'] === 'income') $income = (float) $r['total'];
                if ($r['type'] === 'expense') $expense = (float) $r['total'];
            }

            return ['income' => $income, 'expense' => $expense, 'balance' => $income - $expense, 'degraded' => false];
        } catch (\Throwable $e) {
            static::logModelFailure('summary', $e, ['organization_id' => $orgId]);
            return ['income' => 0.0, 'expense' => 0.0, 'balance' => 0.0, 'degraded' => true];
        }
    }

    public static function getCategories(int $orgId, ?string $type = null): array
    {
        try {
            $pdo = Database::connection();
            $sql = "SELECT * FROM financial_categories WHERE organization_id = :org";
            $params = ['org' => $orgId];
            if ($type) { $sql .= " AND type = :type"; $params['type'] = $type; }
            $sql .= " ORDER BY name";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            static::logModelFailure('getCategories', $e, ['organization_id' => $orgId]);
            return [];
        }
    }
}
