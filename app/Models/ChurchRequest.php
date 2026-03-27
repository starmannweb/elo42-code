<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class ChurchRequest extends Model
{
    protected static string $table = 'requests';
    protected static array $fillable = ['organization_id','member_id','title','description','type','priority','status','resolved_at','created_by'];

    public static function byOrg(int $orgId, ?string $status = null): array
    {
        $pdo = Database::connection();
        $sql = "SELECT r.*, m.name as member_name FROM requests r LEFT JOIN members m ON r.member_id = m.id WHERE r.organization_id = :org";
        $params = ['org' => $orgId];
        if ($status) { $sql .= " AND r.status = :st"; $params['st'] = $status; }
        $sql .= " ORDER BY FIELD(r.priority,'urgent','high','normal','low'), r.created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function countOpen(int $orgId): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM requests WHERE organization_id = :org AND status IN ('open','in_progress')");
        $stmt->execute(['org' => $orgId]);
        return (int) $stmt->fetchColumn();
    }
}
