<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected static string $table = 'users';

    protected static array $fillable = [
        'name', 'email', 'password', 'phone', 'avatar',
        'email_verified_at', 'status', 'last_login_at',
    ];

    public static function findByEmail(string $email): ?array
    {
        return static::first('email', $email);
    }

    public static function createAccount(array $data): int|string
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        return static::create($data);
    }

    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public static function hasOrganization(int $userId): bool
    {
        try {
            $pdo = \App\Core\Database::connection();
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM organization_users WHERE user_id = :uid AND status = 'active'");
            $stmt->execute(['uid' => $userId]);
            return (int) $stmt->fetch()['total'] > 0;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function getOrganization(int $userId): ?array
    {
        try {
            $pdo = \App\Core\Database::connection();
            $stmt = $pdo->prepare("
                SELECT o.*, ou.role_id, r.slug as role_slug, r.name as role_name
                FROM organizations o
                JOIN organization_users ou ON o.id = ou.organization_id
                LEFT JOIN roles r ON ou.role_id = r.id
                WHERE ou.user_id = :uid AND ou.status = 'active'
                LIMIT 1
            ");
            $stmt->execute(['uid' => $userId]);
            $result = $stmt->fetch();
            return $result ?: null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public static function getPermissions(int $userId): array
    {
        try {
            $pdo = \App\Core\Database::connection();
            $stmt = $pdo->prepare("
                SELECT DISTINCT p.slug
                FROM permissions p
                JOIN role_permissions rp ON p.id = rp.permission_id
                JOIN roles r ON r.id = rp.role_id
                JOIN organization_users ou ON ou.role_id = r.id
                WHERE ou.user_id = :uid AND ou.status = 'active'
            ");
            $stmt->execute(['uid' => $userId]);
            return $stmt->fetchAll(\PDO::FETCH_COLUMN);
        } catch (\Throwable $e) {
            return [];
        }
    }
}
