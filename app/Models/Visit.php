<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Visit extends Model
{
    protected static string $table = 'visits';
    protected static array $fillable = ['organization_id','visitor_name','phone','email','visit_date','source','notes','follow_up','assigned_to'];

    public static function byOrg(int $orgId, array $filters = []): array
    {
        try {
            $pdo = Database::connection();
            $sql = "SELECT v.*, m.name as assigned_name 
                    FROM visits v 
                    LEFT JOIN members m ON v.assigned_to = m.id 
                    WHERE v.organization_id = :org";
            $params = ['org' => $orgId];

            if (!empty($filters['search'])) {
                $sql .= " AND (v.visitor_name LIKE :search OR v.email LIKE :search OR v.phone LIKE :search)";
                $params['search'] = '%' . $filters['search'] . '%';
            }

            if (!empty($filters['status'])) {
                $sql .= " AND v.follow_up = :status";
                $params['status'] = $filters['status'];
            }

            if (!empty($filters['month'])) {
                $sql .= " AND v.visit_date LIKE :month";
                $params['month'] = $filters['month'] . '%';
            }

            $sql .= " ORDER BY v.visit_date DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            static::logModelFailure('byOrg', $e, ['organization_id' => $orgId]);
            return [];
        }
    }
}
