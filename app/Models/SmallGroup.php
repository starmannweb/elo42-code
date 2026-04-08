<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class SmallGroup extends Model
{
    protected static string $table = 'small_groups';
    protected static array $fillable = [
        'organization_id', 'name', 'description', 'leader_member_id',
        'co_leader_member_id', 'meeting_day', 'meeting_time', 'location',
        'max_members', 'status',
    ];

    public static function byOrg(int $orgId, string $search = ''): array
    {
        try {
            $pdo = Database::connection();
            $sql = "SELECT sg.*, m.name as leader_name, 
                    (SELECT COUNT(*) FROM small_group_members WHERE small_group_id = sg.id) as member_count
                    FROM small_groups sg
                    LEFT JOIN members m ON sg.leader_member_id = m.id
                    WHERE sg.organization_id = :oid";
            if ($search !== '') {
                $sql .= " AND sg.name LIKE :search";
            }
            $sql .= " ORDER BY sg.name ASC";
            $stmt = $pdo->prepare($sql);
            $params = ['oid' => $orgId];
            if ($search !== '') { $params['search'] = "%{$search}%"; }
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (\Throwable $e) { return []; }
    }

    public static function getWithMembers(int $id): ?array
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("SELECT * FROM small_groups WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $group = $stmt->fetch();
            if (!$group) return null;

            $stmt2 = $pdo->prepare("
                SELECT sgm.*, m.name, m.phone, m.email 
                FROM small_group_members sgm
                JOIN members m ON sgm.member_id = m.id
                WHERE sgm.small_group_id = :gid ORDER BY m.name
            ");
            $stmt2->execute(['gid' => $id]);
            $group['members'] = $stmt2->fetchAll();
            return $group;
        } catch (\Throwable $e) { return null; }
    }
}
