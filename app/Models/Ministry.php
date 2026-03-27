<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Ministry extends Model
{
    protected static string $table = 'ministries';
    protected static array $fillable = ['organization_id', 'name', 'description', 'leader_member_id', 'color', 'status'];

    public static function byOrg(int $orgId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("
            SELECT mi.*, m.name as leader_name,
            (SELECT COUNT(*) FROM ministry_members mm WHERE mm.ministry_id = mi.id) as member_count
            FROM ministries mi
            LEFT JOIN members m ON mi.leader_member_id = m.id
            WHERE mi.organization_id = :org ORDER BY mi.name
        ");
        $stmt->execute(['org' => $orgId]);
        return $stmt->fetchAll();
    }

    public static function countByOrg(int $orgId): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM ministries WHERE organization_id = :org AND status = 'active'");
        $stmt->execute(['org' => $orgId]);
        return (int) $stmt->fetchColumn();
    }

    public static function getMembers(int $ministryId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("
            SELECT m.*, mm.role, mm.joined_at FROM members m
            JOIN ministry_members mm ON m.id = mm.member_id
            WHERE mm.ministry_id = :mid ORDER BY m.name
        ");
        $stmt->execute(['mid' => $ministryId]);
        return $stmt->fetchAll();
    }

    public static function syncMembers(int $ministryId, array $memberIds): void
    {
        $pdo = Database::connection();
        $pdo->prepare("DELETE FROM ministry_members WHERE ministry_id = :mid")->execute(['mid' => $ministryId]);
        $stmt = $pdo->prepare("INSERT INTO ministry_members (ministry_id, member_id) VALUES (:mid, :memid)");
        foreach ($memberIds as $memId) {
            $stmt->execute(['mid' => $ministryId, 'memid' => $memId]);
        }
    }
}
