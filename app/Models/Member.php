<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Member extends Model
{
    protected static string $table = 'members';

    protected static array $fillable = [
        'organization_id', 'name', 'email', 'phone', 'birth_date',
        'gender', 'marital_status', 'address', 'city', 'state', 'zip_code',
        'photo', 'membership_date', 'baptism_date', 'status', 'notes', 'created_by',
    ];

    public static function byOrg(int $orgId, array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $pdo = Database::connection();
        $where = ['m.organization_id = :org_id'];
        $params = ['org_id' => $orgId];

        if (!empty($filters['search'])) {
            $where[] = "(m.name LIKE :search OR m.email LIKE :search OR m.phone LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['status'])) {
            $where[] = "m.status = :status";
            $params['status'] = $filters['status'];
        }

        $whereStr = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM members m WHERE {$whereStr}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $stmt = $pdo->prepare("
            SELECT m.* FROM members m
            WHERE {$whereStr}
            ORDER BY m.name ASC
            LIMIT {$perPage} OFFSET {$offset}
        ");
        $stmt->execute($params);

        return [
            'data'       => $stmt->fetchAll(),
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'totalPages' => (int) ceil($total / $perPage),
        ];
    }

    public static function countByOrg(int $orgId, ?string $status = null): int
    {
        $pdo = Database::connection();
        $sql = "SELECT COUNT(*) FROM members WHERE organization_id = :org";
        $params = ['org' => $orgId];
        if ($status) {
            $sql .= " AND status = :status";
            $params['status'] = $status;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public static function newThisMonth(int $orgId): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM members
            WHERE organization_id = :org
            AND created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')
        ");
        $stmt->execute(['org' => $orgId]);
        return (int) $stmt->fetchColumn();
    }
}
