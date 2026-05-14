<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Member extends Model
{
    protected static string $table = 'members';

    protected static array $fillable = [
        'organization_id', 'church_unit_id', 'name', 'email', 'phone', 'birth_date',
        'gender', 'marital_status', 'address', 'city', 'state', 'zip_code',
        'latitude', 'longitude', 'photo', 'membership_date', 'baptism_date', 'status', 'notes', 'created_by',
    ];

    public static function byOrg(int $orgId, array $filters = [], int $page = 1, int $perPage = 20): array
    {
        try {
            $pdo = Database::connection();
            $where = ['m.organization_id = :org_id'];
            $params = ['org_id' => $orgId];

            if (!empty($filters['search'])) {
                $where[] = "(m.name LIKE :search OR m.email LIKE :search OR m.phone LIKE :search)";
                $params['search'] = '%' . $filters['search'] . '%';
            }
            if (!empty($filters['status'])) {
                $where[] = "m.status = :status";
                $params['status'] = $filters['status'];
            }

            $whereStr = implode(' AND ', $where);
            $offset = ($page - 1) * $perPage;

            $countStmt = $pdo->prepare("SELECT COUNT(*) FROM members m WHERE {$whereStr}");
            $countStmt->execute($params);
            $total = (int) $countStmt->fetchColumn();

            $stmt = $pdo->prepare("
                SELECT m.*, u.name AS unit_name FROM members m
                LEFT JOIN church_units u ON u.id = m.church_unit_id
                WHERE {$whereStr}
                ORDER BY m.name ASC
                LIMIT {$perPage} OFFSET {$offset}
            ");
            $stmt->execute($params);

            return [
                'data'       => $stmt->fetchAll(),
                'total'      => $total,
                'page'       => $page,
                'perPage'    => $perPage,
                'totalPages' => (int) ceil($total / $perPage),
                'degraded'   => false,
            ];
        } catch (\Throwable $e) {
            return [
                'data'       => [],
                'total'      => 0,
                'page'       => max(1, $page),
                'perPage'    => $perPage,
                'totalPages' => 1,
                'degraded'   => true,
            ];
        }
    }

    public static function countByOrg(int $orgId, ?string $status = null): int
    {
        try {
            $pdo = Database::connection();
            $sql = "SELECT COUNT(*) FROM members WHERE organization_id = :org";
            $params = ['org' => $orgId];
            if ($status) {
                $sql .= " AND status = :status";
                $params['status'] = $status;
            }
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return (int) $stmt->fetchColumn();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    public static function newThisMonth(int $orgId): int
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("
                SELECT COUNT(*) FROM members
                WHERE organization_id = :org
                AND created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')
            ");
            $stmt->execute(['org' => $orgId]);
            return (int) $stmt->fetchColumn();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    public static function getDemographics(int $orgId): array
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("
                SELECT gender, birth_date
                FROM members
                WHERE organization_id = :org AND status = 'active'
            ");
            $stmt->execute(['org' => $orgId]);
            $members = $stmt->fetchAll();

            $demographics = [
                'gender' => ['M' => 0, 'F' => 0, 'other' => 0],
                'age' => ['0-12' => 0, '13-17' => 0, '18-25' => 0, '26-35' => 0, '36-50' => 0, '51+' => 0, 'unknown' => 0]
            ];

            $now = new \DateTime();

            foreach ($members as $member) {
                // Gender
                $g = strtoupper(trim((string) ($member['gender'] ?? '')));
                if ($g === 'M' || $g === 'MALE' || $g === 'MASCULINO') {
                    $demographics['gender']['M']++;
                } elseif ($g === 'F' || $g === 'FEMALE' || $g === 'FEMININO') {
                    $demographics['gender']['F']++;
                } else {
                    $demographics['gender']['other']++;
                }

                // Age
                if (!empty($member['birth_date'])) {
                    try {
                        $birthDate = new \DateTime($member['birth_date']);
                        $age = $now->diff($birthDate)->y;

                        if ($age <= 12) $demographics['age']['0-12']++;
                        elseif ($age <= 17) $demographics['age']['13-17']++;
                        elseif ($age <= 25) $demographics['age']['18-25']++;
                        elseif ($age <= 35) $demographics['age']['26-35']++;
                        elseif ($age <= 50) $demographics['age']['36-50']++;
                        else $demographics['age']['51+']++;
                    } catch (\Exception $e) {
                        $demographics['age']['unknown']++;
                    }
                } else {
                    $demographics['age']['unknown']++;
                }
            }

            return $demographics;
        } catch (\Throwable $e) {
            return [
                'gender' => ['M' => 0, 'F' => 0, 'other' => 0],
                'age' => ['0-12' => 0, '13-17' => 0, '18-25' => 0, '26-35' => 0, '36-50' => 0, '51+' => 0, 'unknown' => 0]
            ];
        }
    }
}
