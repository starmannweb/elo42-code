<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Visit extends Model
{
    protected static string $table = 'visits';
    protected static array $fillable = ['organization_id','visitor_name','phone','email','visit_date','source','notes','follow_up','assigned_to'];

    public static function byOrg(int $orgId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT v.*, m.name as assigned_name FROM visits v LEFT JOIN members m ON v.assigned_to = m.id WHERE v.organization_id = :org ORDER BY v.visit_date DESC");
        $stmt->execute(['org' => $orgId]);
        return $stmt->fetchAll();
    }
}
