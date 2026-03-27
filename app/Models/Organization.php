<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Organization extends Model
{
    protected static string $table = 'organizations';

    protected static array $fillable = [
        'name', 'slug', 'type', 'document', 'email', 'phone',
        'address', 'city', 'state', 'zip_code', 'logo', 'website',
        'plan', 'status', 'trial_ends_at', 'settings',
    ];

    public static function createWithOwner(array $orgData, int $userId): int|string
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();

        try {
            $orgData['slug'] = static::generateSlug($orgData['name']);
            $orgData['status'] = 'trial';
            $orgData['trial_ends_at'] = date('Y-m-d H:i:s', strtotime('+30 days'));

            $orgId = static::create($orgData);

            $managerRole = $pdo->prepare("SELECT id FROM roles WHERE slug = 'org-manager' LIMIT 1");
            $managerRole->execute();
            $roleId = $managerRole->fetch()['id'] ?? null;

            $stmt = $pdo->prepare("
                INSERT INTO organization_users (organization_id, user_id, role_id, status, joined_at)
                VALUES (:org_id, :user_id, :role_id, 'active', NOW())
            ");
            $stmt->execute([
                'org_id'  => $orgId,
                'user_id' => $userId,
                'role_id' => $roleId,
            ]);

            $pdo->commit();
            return $orgId;
        } catch (\Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function generateSlug(string $name): string
    {
        $slug = mb_strtolower($name);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');

        $original = $slug;
        $counter = 1;

        while (static::first('slug', $slug)) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
