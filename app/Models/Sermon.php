<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Sermon extends Model
{
    protected static string $table = 'sermons';
    protected static array $fillable = ['organization_id','title','preacher','sermon_date','bible_reference','summary','series_name','tags','status'];

    public static function byOrg(int $orgId, ?string $search = null): array
    {
        try {
            $pdo = Database::connection();
            $sql = "SELECT * FROM sermons WHERE organization_id = :org";
            $params = ['org' => $orgId];
            if ($search) { $sql .= " AND (title LIKE :s OR preacher LIKE :s OR bible_reference LIKE :s)"; $params['s'] = "%{$search}%"; }
            $sql .= " ORDER BY sermon_date DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            static::logModelFailure('byOrg', $e, ['organization_id' => $orgId]);
            return [];
        }
    }
}
