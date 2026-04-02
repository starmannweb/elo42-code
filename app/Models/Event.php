<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Event extends Model
{
    protected static string $table = 'events';
    protected static array $fillable = ['organization_id','title','description','location','start_date','end_date','max_registrations','status','created_by'];

    public static function byOrg(int $orgId, ?string $status = null): array
    {
        try {
            $pdo = Database::connection();
            $sql = "SELECT e.*, (SELECT COUNT(*) FROM event_registrations er WHERE er.event_id = e.id) as registrations
                    FROM events e WHERE e.organization_id = :org";
            $params = ['org' => $orgId];
            if ($status) { $sql .= " AND e.status = :status"; $params['status'] = $status; }
            $sql .= " ORDER BY e.start_date DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            static::logModelFailure('byOrg', $e, ['organization_id' => $orgId]);
            return [];
        }
    }

    public static function countActive(int $orgId): int
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM events WHERE organization_id = :org AND status IN ('published','ongoing')");
            $stmt->execute(['org' => $orgId]);
            return (int) $stmt->fetchColumn();
        } catch (\Throwable $e) {
            static::logModelFailure('countActive', $e, ['organization_id' => $orgId]);
            return 0;
        }
    }

    public static function getRegistrations(int $eventId): array
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("SELECT er.*, m.name as member_name FROM event_registrations er LEFT JOIN members m ON er.member_id = m.id WHERE er.event_id = :eid ORDER BY er.created_at DESC");
            $stmt->execute(['eid' => $eventId]);
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            static::logModelFailure('getRegistrations', $e, ['event_id' => $eventId]);
            return [];
        }
    }
}
