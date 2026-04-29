<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Benefit extends Model
{
    protected static string $table = 'benefits';
    protected static array $fillable = ['name','slug','service_id','description','requirements','status','max_usage','duration_days','valid_until'];

    public static function allWithUsageCount(): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->query("
            SELECT b.*, s.name as service_name,
                (SELECT COUNT(*) FROM benefit_usages bu WHERE bu.benefit_id = b.id) as usage_count
            FROM benefits b
            LEFT JOIN services s ON s.id = b.service_id
            ORDER BY b.name
        ");
        return $stmt->fetchAll();
    }
}
