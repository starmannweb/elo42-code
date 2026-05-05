<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class PlatformSetting extends Model
{
    protected static string $table = 'platform_settings';
    protected static array $fillable = ['setting_key','setting_value','setting_group','description','updated_by'];

    public static function get(string $key, ?string $default = null): ?string
    {
        $row = static::first('setting_key', $key);
        return $row ? $row['setting_value'] : $default;
    }

    public static function set(string $key, ?string $value, ?int $userId = null): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("UPDATE platform_settings SET setting_value = :val, updated_by = :uid WHERE setting_key = :key");
        $stmt->execute(['val' => $value, 'uid' => $userId, 'key' => $key]);

        if ($stmt->rowCount() === 0 && !static::first('setting_key', $key)) {
            $insert = $pdo->prepare("
                INSERT INTO platform_settings (setting_key, setting_value, setting_group, description, updated_by)
                VALUES (:key, :val, 'general', '', :uid)
            ");
            $insert->execute(['key' => $key, 'val' => $value, 'uid' => $userId]);
        }
    }

    public static function byGroup(?string $group = null): array
    {
        $pdo = Database::connection();
        if ($group) {
            $stmt = $pdo->prepare("SELECT * FROM platform_settings WHERE setting_group = :g ORDER BY setting_key");
            $stmt->execute(['g' => $group]);
        } else {
            $stmt = $pdo->query("SELECT * FROM platform_settings ORDER BY setting_group, setting_key");
        }
        return $stmt->fetchAll();
    }
}
