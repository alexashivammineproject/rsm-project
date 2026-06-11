<?php
/**
 * Server Status Diagnostic Script
 * Upload this to server and access via browser
 */

echo "<h1>Server Diagnostic Report</h1>";
echo "<pre>";

// 1. PHP Version
echo "=== PHP VERSION ===\n";
echo "PHP Version: " . phpversion() . "\n\n";

// 2. Required Extensions
echo "=== REQUIRED EXTENSIONS ===\n";
$required_extensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath'];
foreach ($required_extensions as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? "✓ LOADED" : "✗ MISSING") . "\n";
}
echo "fileinfo: " . (extension_loaded('fileinfo') ? "✓ LOADED" : "✗ MISSING (OK - we have workaround)") . "\n\n";

// 3. File Paths
echo "=== FILE PATHS ===\n";
echo "Current directory: " . __DIR__ . "\n";
echo "Document root: " . $_SERVER['DOCUMENT_ROOT'] . "\n\n";

// 4. Critical Files Exist
echo "=== CRITICAL FILES ===\n";
$critical_files = [
    '.env',
    'vendor/autoload.php',
    'bootstrap/app.php',
    'app/Providers/AppServiceProvider.php',
    'app/helpers.php',
    'public/index.php'
];

foreach ($critical_files as $file) {
    $path = __DIR__ . '/' . $file;
    echo "$file: " . (file_exists($path) ? "✓ EXISTS" : "✗ MISSING") . "\n";
}
echo "\n";

// 5. Storage Permissions
echo "=== STORAGE PERMISSIONS ===\n";
$dirs = ['storage', 'storage/logs', 'storage/framework', 'storage/app', 'bootstrap/cache'];
foreach ($dirs as $dir) {
    $path = __DIR__ . '/' . $dir;
    if (is_dir($path)) {
        echo "$dir: " . substr(sprintf('%o', fileperms($path)), -4) . " (" . (is_writable($path) ? "writable" : "NOT writable") . ")\n";
    } else {
        echo "$dir: ✗ MISSING\n";
    }
}
echo "\n";

// 6. Try to load Laravel
echo "=== LARAVEL BOOTSTRAP TEST ===\n";
try {
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require __DIR__ . '/vendor/autoload.php';
        echo "✓ Autoload loaded successfully\n";
        
        if (file_exists(__DIR__ . '/bootstrap/app.php')) {
            $app = require_once __DIR__ . '/bootstrap/app.php';
            echo "✓ Laravel app bootstrapped successfully\n";
            
            // Check if helpers are loaded
            if (function_exists('safe_storage_url')) {
                echo "✓ Custom helpers loaded successfully\n";
            } else {
                echo "✗ Custom helpers NOT loaded\n";
            }
        } else {
            echo "✗ bootstrap/app.php not found\n";
        }
    } else {
        echo "✗ vendor/autoload.php not found - Composer not installed!\n";
    }
} catch (Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
echo "\n";

// 7. Check .env
echo "=== ENVIRONMENT CONFIGURATION ===\n";
if (file_exists(__DIR__ . '/.env')) {
    $env_content = file_get_contents(__DIR__ . '/.env');
    echo "✓ .env file exists\n";
    echo "APP_ENV: " . (preg_match('/APP_ENV=(\w+)/', $env_content, $m) ? $m[1] : 'NOT SET') . "\n";
    echo "APP_DEBUG: " . (preg_match('/APP_DEBUG=(true|false)/', $env_content, $m) ? $m[1] : 'NOT SET') . "\n";
    echo "DB_CONNECTION: " . (preg_match('/DB_CONNECTION=(\w+)/', $env_content, $m) ? $m[1] : 'NOT SET') . "\n";
} else {
    echo "✗ .env file NOT FOUND\n";
}
echo "\n";

// 8. Latest Laravel Log
echo "=== LATEST LARAVEL LOG ===\n";
$log_file = __DIR__ . '/storage/logs/laravel.log';
if (file_exists($log_file)) {
    $log_content = file_get_contents($log_file);
    $lines = explode("\n", $log_content);
    $last_50_lines = array_slice($lines, -50);
    echo implode("\n", $last_50_lines);
} else {
    echo "No log file found\n";
}

echo "</pre>";
?>
