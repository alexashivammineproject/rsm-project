<?php
/**
 * Simple Check - https://rsmmultilink.com/check.php
 */
header('Content-Type: text/plain');

echo "=== QUICK SERVER CHECK ===\n\n";

echo "1. PHP Version: " . phpversion() . "\n";
echo "2. Current Time: " . date('Y-m-d H:i:s') . "\n";
echo "3. Script Path: " . __FILE__ . "\n";
echo "4. Root Dir: " . __DIR__ . "\n\n";

echo "=== FILE CHECK ===\n";
$files = [
    'public/index.php',
    'server.php',
    '.env',
    '.htaccess',
    'vendor/autoload.php',
];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    echo sprintf("%-30s %s\n", $file . ':', file_exists($path) ? '✓ EXISTS' : '✗ MISSING');
}

echo "\n=== MALWARE CHECK ===\n";
$malware = ['filefuns.php', 'goods.php', 'shell.php'];
$found = [];
foreach ($malware as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        $found[] = $file;
        echo "⚠️  FOUND: $file\n";
    }
}
if (empty($found)) {
    echo "✓ No malware found\n";
}

echo "\n=== DATABASE CHECK ===\n";
if (file_exists(__DIR__ . '/.env')) {
    $env = parse_ini_file(__DIR__ . '/.env');
    $db = $env['DB_DATABASE'] ?? 'not set';
    $user = $env['DB_USERNAME'] ?? 'not set';
    $pass = isset($env['DB_PASSWORD']) ? str_repeat('*', min(10, strlen($env['DB_PASSWORD']))) : 'not set';
    
    echo "Database: $db\n";
    echo "Username: $user\n";
    echo "Password: $pass\n\n";
    
    try {
        $pdo = new PDO(
            "mysql:host={$env['DB_HOST']};dbname=$db",
            $user,
            $env['DB_PASSWORD']
        );
        echo "✓ DATABASE CONNECTION: SUCCESS\n";
    } catch (PDOException $e) {
        echo "✗ DATABASE CONNECTION: FAILED\n";
        echo "Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "✗ .env file not found\n";
}

echo "\n=== END CHECK ===\n";
echo "\nVisit: https://www.rsmmultilink.com/emergency-fix.php for full fix\n";
?>
