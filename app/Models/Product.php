<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Product extends Model
{
    protected static string $table = 'products';
    protected static array $fillable = ['category_id','name','slug','description','price','features','status','is_featured','sort_order'];

    public static function allWithCategory(): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->query("SELECT p.*, pc.name as category_name FROM products p LEFT JOIN product_categories pc ON p.category_id = pc.id ORDER BY p.sort_order, p.name");
        return $stmt->fetchAll();
    }

    public static function getCategories(): array
    {
        $pdo = Database::connection();
        return $pdo->query("SELECT * FROM product_categories ORDER BY sort_order, name")->fetchAll();
    }

    public static function createCategory(array $data): int|string
    {
        $pdo = Database::connection();
        $pdo->prepare("INSERT INTO product_categories (name, slug, description, sort_order) VALUES (:name, :slug, :desc, :sort)")
            ->execute(['name' => $data['name'], 'slug' => $data['slug'], 'desc' => $data['description'] ?? null, 'sort' => $data['sort_order'] ?? 0]);
        return $pdo->lastInsertId();
    }
}
