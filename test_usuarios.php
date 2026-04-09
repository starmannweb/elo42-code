<?php

require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = require __DIR__ . '/bootstrap/app.php';

try {
    \App\Core\Database::connection()->query("SELECT 1");
    
    // Simulate what the controller does
    $orgId = 1; // Assuming org 1 exists
    $teamMembers = \App\Models\Organization::getUsers((int) $orgId);
    
    $pdo = \App\Core\Database::connection();
    $stmt = $pdo->prepare("SELECT id, name FROM roles WHERE slug LIKE 'org-%' ORDER BY name ASC");
    $stmt->execute();
    $availableRoles = $stmt->fetchAll();
    
    echo "Success! Found " . count($teamMembers) . " members and " . count($availableRoles) . " roles.\n";
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
