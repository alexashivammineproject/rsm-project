<?php
/**
 * Database Connection Test
 * Access: https://rsmmultilink.com/test-db.php
 */

header('Content-Type: text/html; charset=utf-8');
echo "<h1>Database Connection Test</h1>";
echo "<hr>";

// Load .env file
$envFile = __DIR__ . '/.env';
if (!file_exists($envFile)) {
    die("❌ .env file not found!");
}

$env = parse_ini_file($envFile);

echo "<h2>Environment Variables:</h2>";
echo "<pre>";
echo "DB_HOST: " . ($env['DB_HOST'] ?? 'NOT SET') . "\n";
echo "DB_PORT: " . ($env['DB_PORT'] ?? 'NOT SET') . "\n";
echo "DB_DATABASE: " . ($env['DB_DATABASE'] ?? 'NOT SET') . "\n";
echo "DB_USERNAME: " . ($env['DB_USERNAME'] ?? 'NOT SET') . "\n";
echo "DB_PASSWORD: " . (isset($env['DB_PASSWORD']) ? str_repeat('*', strlen($env['DB_PASSWORD'])) : 'NOT SET') . "\n";
echo "</pre>";

echo "<h2>Connection Test:</h2>";

$host = $env['DB_HOST'] ?? '127.0.0.1';
$port = $env['DB_PORT'] ?? '3306';
$database = $env['DB_DATABASE'] ?? '';
$username = $env['DB_USERNAME'] ?? '';
$password = $env['DB_PASSWORD'] ?? '';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "✅ <strong style='color: green;'>DATABASE CONNECTION SUCCESSFUL!</strong><br>";
    echo "Server version: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "<br>";
    
    // Test query
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<br>Found " . count($tables) . " tables in database.<br>";
    
    if (count($tables) > 0) {
        echo "<br><strong>Tables:</strong><br>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    }
    
} catch (PDOException $e) {
    echo "❌ <strong style='color: red;'>DATABASE CONNECTION FAILED!</strong><br><br>";
    echo "<strong>Error:</strong> " . $e->getMessage() . "<br><br>";
    
    echo "<h3>🔧 FIX INSTRUCTIONS:</h3>";
    echo "<ol>";
    echo "<li>Login to cPanel</li>";
    echo "<li>Go to MySQL Databases</li>";
    echo "<li>Check if database '<strong>$database</strong>' exists</li>";
    echo "<li>Check if user '<strong>$username</strong>' exists and has access</li>";
    echo "<li>Update password in .env file: <code>/home/rsmmultilink/public_html/.env</code></li>";
    echo "<li>Run: <code>php artisan config:clear && php artisan cache:clear</code></li>";
    echo "</ol>";
}

echo "<hr>";
echo "<h3>🗑 Malware Check:</h3>";

$malwareFiles = ['filefuns.php', 'goods.php', 'shell.php', 'c99.php', 'r57.php'];
$foundMalware = [];

foreach ($malwareFiles as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        $foundMalware[] = $file;
    }
}

if (count($foundMalware) > 0) {
    echo "⚠️ <strong style='color: red;'>MALWARE FILES FOUND:</strong><br>";
    echo "<ul>";
    foreach ($foundMalware as $file) {
        echo "<li style='color: red;'><strong>$file</strong> - DELETE IMMEDIATELY!</li>";
    }
    echo "</ul>";
    echo "<br><strong>Action:</strong> Run cleanup: <code>php cleanup-malware.php</code>";
} else {
    echo "✅ <strong style='color: green;'>No malware files detected</strong>";
}

echo "<hr>";
echo "<p><small>Generated: " . date('Y-m-d H:i:s') . "</small></p>";
echo "<p><strong>⚠️ DELETE THIS FILE after testing for security!</strong></p>";
?>
