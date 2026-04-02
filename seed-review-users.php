<?php

declare(strict_types=1);

define('BASE_PATH', __DIR__);

require BASE_PATH . '/app/autoload.php';

loadEnvironment();
loadConfig();

$options = parseOptions($argv ?? []);
$resetPasswords = array_key_exists('reset-passwords', $options);
$sharedPassword = (string) ($options['password'] ?? 'Elo42@2026!');
$devName = (string) ($options['dev-name'] ?? env('DEV_REVIEW_USER_NAME', 'Ricieri (Dev)'));
$devEmail = (string) ($options['dev-email'] ?? env('DEV_REVIEW_USER_EMAIL', 'ricieri@starmannweb.com.br'));

$accounts = [
    [
        'label' => 'Developer',
        'name' => $devName,
        'email' => $devEmail,
        'role' => 'super-admin',
        'organization' => [
            'name' => 'Elo 42 Administracao',
            'slug' => 'elo42-administracao',
            'type' => 'association',
            'plan' => 'enterprise',
            'status' => 'active',
        ],
    ],
    [
        'label' => 'Reviewer',
        'name' => 'Thiago Guimaraes',
        'email' => 'thiago.guimaraes@review.elo42.local',
        'role' => 'org-manager',
        'organization' => [
            'name' => 'Igreja Presbiteriana - Thiago Guimaraes',
            'slug' => 'igreja-presbiteriana-thiago-guimaraes',
            'type' => 'church',
            'plan' => 'enterprise',
            'status' => 'active',
        ],
    ],
    [
        'label' => 'Reviewer',
        'name' => 'Pr. Eder',
        'email' => 'pr.eder@review.elo42.local',
        'role' => 'org-manager',
        'organization' => [
            'name' => 'Igreja Presbiteriana - Pr. Eder',
            'slug' => 'igreja-presbiteriana-pr-eder',
            'type' => 'church',
            'plan' => 'enterprise',
            'status' => 'active',
        ],
    ],
    [
        'label' => 'Reviewer',
        'name' => 'Pr. Cassio Evaristo',
        'email' => 'cassio.evaristo@review.elo42.local',
        'role' => 'org-manager',
        'organization' => [
            'name' => 'Igreja Casa Klaus',
            'slug' => 'igreja-casa-klaus',
            'type' => 'church',
            'plan' => 'enterprise',
            'status' => 'active',
        ],
    ],
];

try {
    $pdo = \App\Core\Database::connection();
    $pdo->beginTransaction();

    $results = [];

    foreach ($accounts as $account) {
        $roleId = findRoleId($pdo, (string) $account['role']);
        $organizationId = ensureOrganization($pdo, $account['organization'], (string) $account['email']);
        $userResult = ensureUser($pdo, $account, $sharedPassword, $resetPasswords);
        ensureMembership($pdo, $organizationId, $userResult['user_id'], $roleId);

        $results[] = [
            'label' => $account['label'],
            'name' => $account['name'],
            'email' => $account['email'],
            'password' => $userResult['password_changed'] ? $sharedPassword : '(mantida)',
            'role' => $account['role'],
            'organization' => $account['organization']['name'],
            'created' => $userResult['created'] ? 'yes' : 'no',
        ];
    }

    $pdo->commit();

    echo "Review users ensured successfully.\n\n";
    echo "Shared password for new accounts: {$sharedPassword}\n";
    if (!$resetPasswords) {
        echo "Existing users kept their current password. Use --reset-passwords to redefine it.\n";
    }
    echo "\n";

    foreach ($results as $result) {
        echo "[{$result['label']}] {$result['name']}\n";
        echo "  Email: {$result['email']}\n";
        echo "  Password: {$result['password']}\n";
        echo "  Role: {$result['role']}\n";
        echo "  Organization: {$result['organization']}\n";
        echo "  Created now: {$result['created']}\n\n";
    }
} catch (\Throwable $e) {
    if (isset($pdo) && $pdo instanceof \PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    fwrite(STDERR, "Error seeding review users: {$e->getMessage()}\n");
    exit(1);
}

function loadEnvironment(): void
{
    $envFile = BASE_PATH . '/.env';
    if (!file_exists($envFile)) {
        return;
    }

    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        if (!str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");

        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
        putenv("{$key}={$value}");
    }
}

function loadConfig(): void
{
    foreach (glob(BASE_PATH . '/config/*.php') as $file) {
        $key = basename($file, '.php');
        $GLOBALS['__config'][$key] = require $file;
    }
}

function parseOptions(array $argv): array
{
    $options = [];

    foreach ($argv as $argument) {
        if (!str_starts_with((string) $argument, '--')) {
            continue;
        }

        $argument = substr((string) $argument, 2);

        if (str_contains($argument, '=')) {
            [$key, $value] = explode('=', $argument, 2);
            $options[$key] = $value;
            continue;
        }

        $options[$argument] = true;
    }

    return $options;
}

function findRoleId(\PDO $pdo, string $roleSlug): int
{
    $statement = $pdo->prepare('SELECT id FROM roles WHERE slug = :slug LIMIT 1');
    $statement->execute(['slug' => $roleSlug]);
    $roleId = $statement->fetchColumn();

    if ($roleId === false) {
        throw new \RuntimeException("Role not found: {$roleSlug}. Run migrations before seeding review users.");
    }

    return (int) $roleId;
}

function ensureOrganization(\PDO $pdo, array $organization, string $fallbackEmail): int
{
    $statement = $pdo->prepare('SELECT id FROM organizations WHERE slug = :slug LIMIT 1');
    $statement->execute(['slug' => $organization['slug']]);
    $organizationId = $statement->fetchColumn();

    $payload = [
        'name' => $organization['name'],
        'slug' => $organization['slug'],
        'type' => $organization['type'],
        'email' => $fallbackEmail,
        'plan' => $organization['plan'],
        'status' => $organization['status'],
        'settings' => json_encode([
            'seed_source' => 'seed-review-users.php',
        ], JSON_UNESCAPED_SLASHES),
    ];

    if ($organizationId !== false) {
        $payload['id'] = (int) $organizationId;

        $update = $pdo->prepare(
            'UPDATE organizations
             SET name = :name, type = :type, email = :email, plan = :plan, status = :status, settings = :settings
             WHERE id = :id'
        );
        $update->execute($payload);

        return (int) $organizationId;
    }

    $insert = $pdo->prepare(
        'INSERT INTO organizations (name, slug, type, email, plan, status, settings)
         VALUES (:name, :slug, :type, :email, :plan, :status, :settings)'
    );
    $insert->execute($payload);

    return (int) $pdo->lastInsertId();
}

function ensureUser(\PDO $pdo, array $account, string $sharedPassword, bool $resetPasswords): array
{
    $statement = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
    $statement->execute(['email' => $account['email']]);
    $userId = $statement->fetchColumn();

    $passwordChanged = false;

    if ($userId !== false) {
        $payload = [
            'id' => (int) $userId,
            'name' => $account['name'],
            'email' => $account['email'],
            'status' => 'active',
            'verified_at' => date('Y-m-d H:i:s'),
        ];

        $sql = 'UPDATE users
                SET name = :name, email = :email, status = :status, email_verified_at = COALESCE(email_verified_at, :verified_at)';

        if ($resetPasswords) {
            $sql .= ', password = :password';
            $payload['password'] = password_hash($sharedPassword, PASSWORD_BCRYPT, ['cost' => 12]);
            $passwordChanged = true;
        }

        $sql .= ' WHERE id = :id';

        $update = $pdo->prepare($sql);
        $update->execute($payload);

        return [
            'user_id' => (int) $userId,
            'created' => false,
            'password_changed' => $passwordChanged,
        ];
    }

    $insert = $pdo->prepare(
        'INSERT INTO users (name, email, password, email_verified_at, status)
         VALUES (:name, :email, :password, :verified_at, :status)'
    );
    $insert->execute([
        'name' => $account['name'],
        'email' => $account['email'],
        'password' => password_hash($sharedPassword, PASSWORD_BCRYPT, ['cost' => 12]),
        'verified_at' => date('Y-m-d H:i:s'),
        'status' => 'active',
    ]);

    return [
        'user_id' => (int) $pdo->lastInsertId(),
        'created' => true,
        'password_changed' => true,
    ];
}

function ensureMembership(\PDO $pdo, int $organizationId, int $userId, int $roleId): void
{
    $statement = $pdo->prepare(
        'SELECT id FROM organization_users WHERE organization_id = :organization_id AND user_id = :user_id LIMIT 1'
    );
    $statement->execute([
        'organization_id' => $organizationId,
        'user_id' => $userId,
    ]);
    $membershipId = $statement->fetchColumn();

    if ($membershipId !== false) {
        $update = $pdo->prepare(
            'UPDATE organization_users
             SET role_id = :role_id, status = :status
             WHERE id = :id'
        );
        $update->execute([
            'id' => (int) $membershipId,
            'role_id' => $roleId,
            'status' => 'active',
        ]);
        return;
    }

    $insert = $pdo->prepare(
        'INSERT INTO organization_users (organization_id, user_id, role_id, status, joined_at)
         VALUES (:organization_id, :user_id, :role_id, :status, NOW())'
    );
    $insert->execute([
        'organization_id' => $organizationId,
        'user_id' => $userId,
        'role_id' => $roleId,
        'status' => 'active',
    ]);
}
