<?php
/**
 * Quick Website Status Check
 * Access: https://rsmmultilink.com/status.php
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>RSM Multilink - Status Check</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #4CAF50; padding-bottom: 10px; }
        .status { padding: 15px; margin: 15px 0; border-radius: 5px; }
        .success { background: #d4edda; border-left: 5px solid #28a745; color: #155724; }
        .error { background: #f8d7da; border-left: 5px solid #dc3545; color: #721c24; }
        .warning { background: #fff3cd; border-left: 5px solid #ffc107; color: #856404; }
        .info { background: #d1ecf1; border-left: 5px solid #17a2b8; color: #0c5460; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .btn { display: inline-block; padding: 10px 20px; margin: 10px 5px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 RSM Multilink - Website Status</h1>
        <p><strong>Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        
        <h2>📋 System Checks</h2>
        
        <?php
        // Check 1: PHP Version
        $phpVersion = phpversion();
        echo "<div class='status success'>";
        echo "✅ <strong>PHP Version:</strong> $phpVersion";
        echo "</div>";
        
        // Check 2: Required Extensions
        $requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'json', 'curl', 'zip'];
        $missingExtensions = [];
        
        foreach ($requiredExtensions as $ext) {
            if (!extension_loaded($ext)) {
                $missingExtensions[] = $ext;
            }
        }
        
        if (empty($missingExtensions)) {
            echo "<div class='status success'>";
            echo "✅ <strong>PHP Extensions:</strong> All required extensions loaded";
            echo "</div>";
        } else {
            echo "<div class='status error'>";
            echo "❌ <strong>Missing Extensions:</strong> " . implode(', ', $missingExtensions);
            echo "</div>";
        }
        
        // Check 3: Laravel Files
        if (file_exists(__DIR__ . '/public/index.php')) {
            echo "<div class='status success'>";
            echo "✅ <strong>Laravel Entry:</strong> public/index.php found";
            echo "</div>";
        } else {
            echo "<div class='status error'>";
            echo "❌ <strong>Laravel Entry:</strong> public/index.php NOT FOUND";
            echo "</div>";
        }
        
        // Check 4: Vendor Directory
        if (is_dir(__DIR__ . '/vendor')) {
            echo "<div class='status success'>";
            echo "✅ <strong>Vendor Directory:</strong> Found (Composer dependencies OK)";
            echo "</div>";
        } else {
            echo "<div class='status error'>";
            echo "❌ <strong>Vendor Directory:</strong> NOT FOUND (Run: composer install)";
            echo "</div>";
        }
        
        // Check 5: .env File
        if (file_exists(__DIR__ . '/.env')) {
            echo "<div class='status success'>";
            echo "✅ <strong>.env File:</strong> Found";
            echo "</div>";
            
            // Parse .env
            $env = parse_ini_file(__DIR__ . '/.env');
            
            // Check database config
            $dbConfigured = isset($env['DB_DATABASE']) && isset($env['DB_USERNAME']) && isset($env['DB_PASSWORD']);
            
            if ($dbConfigured) {
                echo "<div class='status info'>";
                echo "ℹ️ <strong>Database Config:</strong><br>";
                echo "Host: " . ($env['DB_HOST'] ?? 'not set') . "<br>";
                echo "Database: " . ($env['DB_DATABASE'] ?? 'not set') . "<br>";
                echo "Username: " . ($env['DB_USERNAME'] ?? 'not set') . "<br>";
                echo "Password: " . (isset($env['DB_PASSWORD']) ? str_repeat('*', min(20, strlen($env['DB_PASSWORD']))) : 'not set');
                echo "</div>";
            }
        } else {
            echo "<div class='status error'>";
            echo "❌ <strong>.env File:</strong> NOT FOUND";
            echo "</div>";
        }
        
        // Check 6: Storage Permissions
        $storageWritable = is_writable(__DIR__ . '/storage');
        if ($storageWritable) {
            echo "<div class='status success'>";
            echo "✅ <strong>Storage Directory:</strong> Writable";
            echo "</div>";
        } else {
            echo "<div class='status error'>";
            echo "❌ <strong>Storage Directory:</strong> NOT Writable (Fix permissions: chmod -R 775 storage)";
            echo "</div>";
        }
        
        // Check 7: Malware Scan
        $malwareFiles = ['filefuns.php', 'goods.php', 'shell.php', 'c99.php', 'r57.php', 'wso.php'];
        $foundMalware = [];
        
        foreach ($malwareFiles as $file) {
            if (file_exists(__DIR__ . '/' . $file)) {
                $foundMalware[] = $file;
            }
        }
        
        if (empty($foundMalware)) {
            echo "<div class='status success'>";
            echo "✅ <strong>Security:</strong> No malware files detected";
            echo "</div>";
        } else {
            echo "<div class='status error'>";
            echo "❌ <strong>SECURITY ALERT:</strong> Malware files found!<br>";
            echo "<ul>";
            foreach ($foundMalware as $file) {
                echo "<li><code>$file</code></li>";
            }
            echo "</ul>";
            echo "<strong>Action:</strong> Run <code>php cleanup-malware.php</code> immediately!";
            echo "</div>";
        }
        
        // Check 8: Error Logs
        $errorLog = __DIR__ . '/storage/logs/laravel.log';
        if (file_exists($errorLog)) {
            $logSize = filesize($errorLog);
            $logSizeMB = round($logSize / 1024 / 1024, 2);
            
            echo "<div class='status info'>";
            echo "ℹ️ <strong>Laravel Log:</strong> $logSizeMB MB<br>";
            
            // Get last 5 lines
            $lines = file($errorLog);
            $lastLines = array_slice($lines, -5);
            
            echo "<br><strong>Last 5 log entries:</strong>";
            echo "<pre style='font-size: 12px; max-height: 200px; overflow-y: auto;'>";
            echo htmlspecialchars(implode('', $lastLines));
            echo "</pre>";
            echo "</div>";
        }
        ?>
        
        <h2>🔗 Quick Actions</h2>
        <a href="test-db.php" class="btn">Test Database Connection</a>
        <a href="/" class="btn">Visit Website Home</a>
        <a href="admin/login" class="btn">Admin Login</a>
        
        <br><br>
        <div class="status warning">
            ⚠️ <strong>IMPORTANT:</strong> Delete this status.php and test-db.php files after debugging for security!
        </div>
        
        <hr>
        <p style="text-align: center; color: #666;">
            <small>RSM Multilink System Status • Generated: <?php echo date('Y-m-d H:i:s'); ?></small>
        </p>
    </div>
</body>
</html>
