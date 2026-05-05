<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class SermonSeries extends Model
{
    protected static string $table = 'sermon_series';

    protected static array $fillable = [
        'organization_id',
        'title',
        'description',
        'bible_reference',
        'status',
    ];

    public static function byOrg(int $orgId): array
    {
        try {
            $stmt = Database::connection()->prepare(
                'SELECT ss.*,
                    (SELECT COUNT(*) FROM sermons s WHERE s.organization_id = ss.organization_id AND s.series_name = ss.title) AS sermons_count
                 FROM sermon_series ss
                 WHERE ss.organization_id = :org_id
                 ORDER BY ss.created_at DESC'
            );
            $stmt->execute(['org_id' => $orgId]);

            return $stmt->fetchAll() ?: [];
        } catch (\Throwable $e) {
            static::logModelFailure('byOrg', $e, ['organization_id' => $orgId]);
            return [];
        }
    }
}
