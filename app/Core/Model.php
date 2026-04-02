<?php

declare(strict_types=1);

namespace App\Core;

use App\Support\Logger;

abstract class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [];

    public static function find(int|string $id): ?array
    {
        $pdo = Database::connection();
        $table = static::$table;
        $pk = static::$primaryKey;

        $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE {$pk} = :id LIMIT 1");
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function all(string $orderBy = 'id', string $direction = 'ASC'): array
    {
        $pdo = Database::connection();
        $table = static::$table;
        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';

        $stmt = $pdo->query("SELECT * FROM {$table} ORDER BY {$orderBy} {$direction}");
        return $stmt->fetchAll();
    }

    public static function where(string $column, mixed $value, string $operator = '='): array
    {
        $pdo = Database::connection();
        $table = static::$table;

        $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE {$column} {$operator} :value");
        $stmt->execute(['value' => $value]);

        return $stmt->fetchAll();
    }

    public static function first(string $column, mixed $value, string $operator = '='): ?array
    {
        $pdo = Database::connection();
        $table = static::$table;

        $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE {$column} {$operator} :value LIMIT 1");
        $stmt->execute(['value' => $value]);

        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function create(array $data): int|string
    {
        $pdo = Database::connection();
        $table = static::$table;
        $data = self::filterFillable($data);

        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $stmt = $pdo->prepare("INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})");
        $stmt->execute($data);

        return $pdo->lastInsertId();
    }

    public static function update(int|string $id, array $data): bool
    {
        $pdo = Database::connection();
        $table = static::$table;
        $pk = static::$primaryKey;
        $data = self::filterFillable($data);

        $sets = [];
        foreach (array_keys($data) as $col) {
            $sets[] = "{$col} = :{$col}";
        }
        $setString = implode(', ', $sets);

        $data['id'] = $id;
        $stmt = $pdo->prepare("UPDATE {$table} SET {$setString} WHERE {$pk} = :id");

        return $stmt->execute($data);
    }

    public static function delete(int|string $id): bool
    {
        $pdo = Database::connection();
        $table = static::$table;
        $pk = static::$primaryKey;

        $stmt = $pdo->prepare("DELETE FROM {$table} WHERE {$pk} = :id");
        return $stmt->execute(['id' => $id]);
    }

    public static function count(string $column = '*', ?string $where = null, array $params = []): int
    {
        $pdo = Database::connection();
        $table = static::$table;

        $sql = "SELECT COUNT({$column}) as total FROM {$table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetch()['total'];
    }

    public static function paginate(int $page = 1, int $perPage = 15, string $orderBy = 'id', string $direction = 'DESC'): array
    {
        $pdo = Database::connection();
        $table = static::$table;
        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';

        $total = static::count();
        $totalPages = (int) ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;

        $stmt = $pdo->prepare("SELECT * FROM {$table} ORDER BY {$orderBy} {$direction} LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data'         => $stmt->fetchAll(),
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'total_pages'  => $totalPages,
        ];
    }

    private static function filterFillable(array $data): array
    {
        if (empty(static::$fillable)) {
            return $data;
        }
        return array_intersect_key($data, array_flip(static::$fillable));
    }

    protected static function logModelFailure(string $operation, \Throwable $e, array $context = []): void
    {
        try {
            (new Logger())->warning('model.operation_failed', array_merge([
                'model' => static::class,
                'table' => static::$table,
                'operation' => $operation,
                'error' => $e->getMessage(),
            ], $context));
        } catch (\Throwable $ignored) {
            // Ignore logger failures in fail-safe mode.
        }
    }
}
