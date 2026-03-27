<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Benefit extends Model
{
    protected static string $table = 'benefits';
    protected static array $fillable = ['name','slug','description','requirements','status','max_usage','valid_until'];

    public static function allWithUsageCount(): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->query("SELECT b.*, (SELECT COUNT(*) FROM benefit_usages bu WHERE bu.benefit_id = b.id) as usage_count FROM benefits b ORDER BY b.name");
        return $stmt->fetchAll();
    }
}
