<?php
/**
 * EMERGENCY FIX SCRIPT
 * Direct URL: https://rsmmultilink.com/emergency-fix.php
 * This will run WITHOUT Laravel and fix everything
 */

// Disable timeout
set_time_limit(300);
ini_set('memory_limit', '512M');

header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Emergency Website Fix</title>
    <meta charset="utf-8">
    <style>
        body { font-family: monospace; background: #000; color: #0f0; padding: 20px; }
        .success { color: #0f0; }
        .error { color: #f00; }
        .warning { color: #ff0; }
        .info { color: #0ff; }
        hr { border-color: #333; margin: 20px 0; }
        pre { background: #111; padding: 10px; border-left: 3px solid #0f0; overflow-x: auto; }
    </style>
</head>
<body>
<h1>🚨 EMERGENCY WEBSITE FIX 🚨</h1>
<p>Started: <?php echo date('Y-m-d H:i:s'); ?></p>
<hr>

<?php

$rootDir = __DIR__;
$fixLog = [];

function logFix($message, $type = 'info') {
    global $fixLog;
    $color = [
        'success' => 'success',
        'error' => 'error',
        'warning' => 'warning',
        'info' => 'info'
    ][$type] ?? 'info';
    
    echo "<p class='$color'>[$type] $message</p>\n";
    $fixLog[] = "[$type] $message";
    flush();
    ob_flush();
}

// ============================================
// STEP 1: DELETE MALWARE FILES
// ============================================
echo "<h2>STEP 1: Deleting Malware Files</h2>\n";

$malwareFiles = [
    'filefuns.php',
    'goods.php',
    'qwe.php',
    'shell.php',
    'c99.php',
    'r57.php',
    'wso.php',
    'adminer.php',
    'alfa.php',
    'symlink.php',
    'idx.php',
];

$deletedCount = 0;
foreach ($malwareFiles as $file) {
    $filepath = $rootDir . '/' . $file;
    if (file_exists($filepath)) {
        if (@unlink($filepath)) {
            logFix("✓ DELETED MALWARE: $file", 'success');
            $deletedCount++;
        } else {
            logFix("✗ FAILED TO DELETE: $file", 'error');
        }
    }
}

logFix("Deleted $deletedCount malware files", 'success');

// ============================================
// STEP 2: CHECK CRITICAL FILES
// ============================================
echo "<h2>STEP 2: Checking Critical Files</h2>\n";

$criticalFiles = [
    'public/index.php',
    'server.php',
    '.env',
    '.htaccess',
    'artisan',
];

foreach ($criticalFiles as $file) {
    if (file_exists($rootDir . '/' . $file)) {
        logFix("✓ EXISTS: $file", 'success');
    } else {
        logFix("✗ MISSING: $file", 'error');
    }
}

// ============================================
// STEP 3: CHECK VENDOR DIRECTORY
// ============================================
echo "<h2>STEP 3: Checking Vendor Directory</h2>\n";

if (is_dir($rootDir . '/vendor')) {
    $vendorFiles = glob($rootDir . '/vendor/*/*');
    logFix("✓ Vendor directory exists (" . count($vendorFiles) . " packages)", 'success');
    
    // Check autoload
    if (file_exists($rootDir . '/vendor/autoload.php')) {
        logFix("✓ vendor/autoload.php exists", 'success');
    } else {
        logFix("✗ vendor/autoload.php MISSING", 'error');
    }
} else {
    logFix("✗ Vendor directory MISSING - Laravel cannot run!", 'error');
}

// ============================================
// STEP 4: TEST DATABASE CONNECTION
// ============================================
echo "<h2>STEP 4: Testing Database Connection</h2>\n";

$envFile = $rootDir . '/.env';
if (file_exists($envFile)) {
    $env = parse_ini_file($envFile);
    
    $dbHost = $env['DB_HOST'] ?? '127.0.0.1';
    $dbPort = $env['DB_PORT'] ?? '3306';
    $dbDatabase = $env['DB_DATABASE'] ?? '';
    $dbUsername = $env['DB_USERNAME'] ?? '';
    $dbPassword = $env['DB_PASSWORD'] ?? '';
    
    logFix("Database: $dbDatabase", 'info');
    logFix("Username: $dbUsername", 'info');
    logFix("Password: " . str_repeat('*', strlen($dbPassword)), 'info');
    
    try {
        $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbDatabase;charset=utf8mb4";
        $pdo = new PDO($dsn, $dbUsername, $dbPassword, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        
        $version = $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
        logFix("✓ DATABASE CONNECTION SUCCESSFUL! (MySQL $version)", 'success');
        
        // Count tables
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        logFix("✓ Found " . count($tables) . " tables in database", 'success');
        
    } catch (PDOException $e) {
        logFix("✗ DATABASE CONNECTION FAILED: " . $e->getMessage(), 'error');
    }
} else {
    logFix("✗ .env file not found", 'error');
}

// ============================================
// STEP 5: CLEAR LARAVEL CACHES
// ============================================
echo "<h2>STEP 5: Clearing Laravel Caches</h2>\n";

chdir($rootDir);

// Clear config cache
$output = [];
exec('php artisan config:clear 2>&1', $output, $return);
if ($return === 0) {
    logFix("✓ Config cache cleared", 'success');
} else {
    logFix("✗ Config cache clear failed: " . implode("\n", $output), 'error');
}

// Clear view cache
$output = [];
exec('php artisan view:clear 2>&1', $output, $return);
if ($return === 0) {
    logFix("✓ View cache cleared", 'success');
} else {
    logFix("✗ View cache clear failed: " . implode("\n", $output), 'error');
}

// Clear route cache
$output = [];
exec('php artisan route:clear 2>&1', $output, $return);
if ($return === 0) {
    logFix("✓ Route cache cleared", 'success');
} else {
    logFix("✗ Route cache clear failed: " . implode("\n", $output), 'error');
}

// Clear application cache
$output = [];
exec('php artisan cache:clear 2>&1', $output, $return);
if ($return === 0) {
    logFix("✓ Application cache cleared", 'success');
} else {
    logFix("✗ Application cache clear failed: " . implode("\n", $output), 'error');
}

// ============================================
// STEP 6: CHECK STORAGE PERMISSIONS
// ============================================
echo "<h2>STEP 6: Checking Storage Permissions</h2>\n";

$storageDir = $rootDir . '/storage';
if (is_writable($storageDir)) {
    logFix("✓ Storage directory is writable", 'success');
} else {
    logFix("✗ Storage directory is NOT writable", 'error');
    
    // Try to fix permissions
    if (@chmod($storageDir, 0775)) {
        logFix("✓ Fixed storage permissions", 'success');
    } else {
        logFix("✗ Could not fix storage permissions - use: chmod -R 775 storage", 'error');
    }
}

// ============================================
// STEP 7: CHECK PHP ERRORS
// ============================================
echo "<h2>STEP 7: Checking Recent PHP Errors</h2>\n";

$laravelLog = $rootDir . '/storage/logs/laravel.log';
if (file_exists($laravelLog)) {
    $logSize = filesize($laravelLog);
    logFix("Laravel log size: " . round($logSize / 1024, 2) . " KB", 'info');
    
    // Get last 10 lines
    $lines = file($laravelLog);
    $lastLines = array_slice($lines, -10);
    
    echo "<pre>";
    foreach ($lastLines as $line) {
        echo htmlspecialchars($line);
    }
    echo "</pre>";
} else {
    logFix("No Laravel log file found", 'info');
}

// ============================================
// STEP 8: TEST LARAVEL BOOTSTRAP
// ============================================
echo "<h2>STEP 8: Testing Laravel Bootstrap</h2>\n";

try {
    if (file_exists($rootDir . '/vendor/autoload.php')) {
        require_once $rootDir . '/vendor/autoload.php';
        logFix("✓ Composer autoload loaded successfully", 'success');
        
        if (file_exists($rootDir . '/bootstrap/app.php')) {
            logFix("✓ Laravel bootstrap file exists", 'success');
        }
    } else {
        logFix("✗ Composer autoload not found", 'error');
    }
} catch (Exception $e) {
    logFix("✗ Bootstrap error: " . $e->getMessage(), 'error');
}

// ============================================
// STEP 9: FIX .HTACCESS PERMISSIONS
// ============================================
echo "<h2>STEP 9: Fixing .htaccess</h2>\n";

$htaccess = $rootDir . '/.htaccess';
if (file_exists($htaccess)) {
    @chmod($htaccess, 0644);
    logFix("✓ .htaccess permissions set to 0644", 'success');
    
    // Check if readable
    if (is_readable($htaccess)) {
        $htaccessSize = filesize($htaccess);
        logFix("✓ .htaccess is readable (" . $htaccessSize . " bytes)", 'success');
    }
} else {
    logFix("✗ .htaccess file not found", 'error');
}

// ============================================
// FINAL SUMMARY
// ============================================
echo "<hr>\n";
echo "<h2>🎯 FIX SUMMARY</h2>\n";

echo "<pre>";
foreach ($fixLog as $log) {
    echo $log . "\n";
}
echo "</pre>";

echo "<hr>\n";
echo "<h2>✅ NEXT STEPS</h2>\n";
echo "<ol>\n";
echo "<li>Visit homepage: <a href='/' style='color:#0ff'>https://www.rsmmultilink.com</a></li>\n";
echo "<li>If still blank, check error log above for specific errors</li>\n";
echo "<li>If database errors, verify credentials in .env file</li>\n";
echo "<li>DELETE THIS FILE after fixing: emergency-fix.php</li>\n";
echo "</ol>\n";

echo "<hr>\n";
echo "<p class='success'>Completed: " . date('Y-m-d H:i:s') . "</p>\n";
echo "<p class='warning'>⚠️ DELETE THIS FILE NOW: emergency-fix.php</p>\n";

?>
</body>
</html>
