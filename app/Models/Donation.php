<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Donation extends Model
{
    protected static string $table = 'donations';
    protected static array $fillable = ['organization_id','church_unit_id','member_id','donor_name','type','amount','donation_date','payment_method','reference','notes'];

    public static function byOrg(int $orgId, array $filters = [], int $page = 1, int $perPage = 20): array
    {
        try {
            $pdo = Database::connection();
            $where = ['d.organization_id = :org'];
            $params = ['org' => $orgId];
            if (!empty($filters['type'])) { $where[] = "d.type = :type"; $params['type'] = $filters['type']; }
            if (!empty($filters['start_date'])) { $where[] = "d.donation_date >= :start"; $params['start'] = $filters['start_date']; }
            if (!empty($filters['end_date'])) { $where[] = "d.donation_date <= :end"; $params['end'] = $filters['end_date']; }
            $whereStr = implode(' AND ', $where);
            $offset = ($page - 1) * $perPage;

            $cStmt = $pdo->prepare("SELECT COUNT(*) FROM donations d WHERE {$whereStr}");
            $cStmt->execute($params);
            $total = (int) $cStmt->fetchColumn();

            $stmt = $pdo->prepare("
                SELECT d.*, m.name as member_name, u.name as unit_name
                FROM donations d
                LEFT JOIN members m ON d.member_id = m.id
                LEFT JOIN church_units u ON u.id = d.church_unit_id
                WHERE {$whereStr}
                ORDER BY d.donation_date DESC
                LIMIT {$perPage} OFFSET {$offset}
            ");
            $stmt->execute($params);
            return [
                'data' => $stmt->fetchAll(),
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => (int) ceil($total / $perPage),
                'degraded' => false,
            ];
        } catch (\Throwable $e) {
            static::logModelFailure('byOrg', $e, ['organization_id' => $orgId]);
            return [
                'data' => [],
                'total' => 0,
                'page' => max(1, $page),
                'perPage' => $perPage,
                'totalPages' => 1,
                'degraded' => true,
            ];
        }
    }

    public static function summaryByType(int $orgId, ?string $startDate = null, ?string $endDate = null): array
    {
        try {
            $pdo = Database::connection();
            $where = "organization_id = :org";
            $params = ['org' => $orgId];
            if ($startDate) { $where .= " AND donation_date >= :start"; $params['start'] = $startDate; }
            if ($endDate) { $where .= " AND donation_date <= :end"; $params['end'] = $endDate; }
            $stmt = $pdo->prepare("SELECT type, SUM(amount) as total, COUNT(*) as count FROM donations WHERE {$where} GROUP BY type ORDER BY total DESC");
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            static::logModelFailure('summaryByType', $e, ['organization_id' => $orgId]);
            return [];
        }
    }

    public static function totalByOrg(int $orgId): float
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("SELECT COALESCE(SUM(amount), 0) FROM donations WHERE organization_id = :org AND donation_date >= DATE_FORMAT(NOW(), '%Y-%m-01')");
            $stmt->execute(['org' => $orgId]);
            return (float) $stmt->fetchColumn();
        } catch (\Throwable $e) {
            static::logModelFailure('totalByOrg', $e, ['organization_id' => $orgId]);
            return 0.0;
        }
    }
}
