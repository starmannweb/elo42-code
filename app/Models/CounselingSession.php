<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class CounselingSession extends Model
{
    protected static string $table = 'counseling_sessions';
    protected static array $fillable = ['organization_id','member_id','counselor_name','subject','session_date','status','notes','is_confidential'];

    public static function byOrg(int $orgId): array
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("SELECT cs.*, m.name as member_name FROM counseling_sessions cs LEFT JOIN members m ON cs.member_id = m.id WHERE cs.organization_id = :org ORDER BY cs.session_date DESC");
            $stmt->execute(['org' => $orgId]);
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            static::logModelFailure('byOrg', $e, ['organization_id' => $orgId]);
            return [];
        }
    }
}
