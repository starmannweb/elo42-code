<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Convert extends Model
{
    protected static string $table = 'converts';
    protected static array $fillable = [
        'organization_id', 'name', 'phone', 'email', 'conversion_date',
        'sponsor_member_id', 'status', 'notes',
    ];

    public static function byOrg(int $orgId, string $status = ''): array
    {
        try {
            $pdo = Database::connection();
            $sql = "SELECT c.*, m.name as sponsor_name 
                    FROM converts c 
                    LEFT JOIN members m ON c.sponsor_member_id = m.id
                    WHERE c.organization_id = :oid";
            if ($status !== '') { $sql .= " AND c.status = :status"; }
            $sql .= " ORDER BY c.created_at DESC";
            $stmt = $pdo->prepare($sql);
            $params = ['oid' => $orgId];
            if ($status !== '') { $params['status'] = $status; }
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (\Throwable $e) { return []; }
    }

    public static function countByOrg(int $orgId): int
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM converts WHERE organization_id = :oid");
            $stmt->execute(['oid' => $orgId]);
            return (int) $stmt->fetchColumn();
        } catch (\Throwable $e) { return 0; }
    }
}
