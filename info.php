<?php
/**
 * Quick Server Info
 * Access: https://www.rsmmultilink.com/info.php
 */

echo "<h1>Quick Server Check</h1><hr>";

echo "<h2>1. PHP Info</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Current File: " . __FILE__ . "<br>";
echo "<br>";

echo "<h2>2. Laravel Files</h2>";
echo "vendor/autoload.php: " . (file_exists(__DIR__ . '/vendor/autoload.php') ? "✓ EXISTS" : "✗ MISSING") . "<br>";
echo ".env: " . (file_exists(__DIR__ . '/.env') ? "✓ EXISTS" : "✗ MISSING") . "<br>";
echo "bootstrap/app.php: " . (file_exists(__DIR__ . '/bootstrap/app.php') ? "✓ EXISTS" : "✗ MISSING") . "<br>";
echo "app/helpers.php: " . (file_exists(__DIR__ . '/app/helpers.php') ? "✓ EXISTS" : "✗ MISSING") . "<br>";
echo "<br>";

echo "<h2>3. Critical Extensions</h2>";
$exts = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'json', 'fileinfo'];
foreach ($exts as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? "✓" : "✗") . "<br>";
}
echo "<br>";

echo "<h2>4. Storage Writable</h2>";
echo "storage: " . (is_writable(__DIR__ . '/storage') ? "✓ WRITABLE" : "✗ NOT WRITABLE") . "<br>";
echo "bootstrap/cache: " . (is_writable(__DIR__ . '/bootstrap/cache') ? "✓ WRITABLE" : "✗ NOT WRITABLE") . "<br>";
echo "<br>";

echo "<h2>5. Try Bootstrap</h2>";
try {
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "✓ Laravel loads successfully!<br>";
    echo "App Version: " . app()->version() . "<br>";
} catch (Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "<br>";
}
echo "<br>";

echo "<h2>6. Latest Error</h2>";
if (file_exists(__DIR__ . '/storage/logs/laravel.log')) {
    $log = file_get_contents(__DIR__ . '/storage/logs/laravel.log');
    $lines = explode("\n", $log);
    $last = array_slice($lines, -10);
    echo "<pre>" . implode("\n", $last) . "</pre>";
} else {
    echo "No log file<br>";
}

phpinfo();
?>
