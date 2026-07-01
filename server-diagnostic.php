<?php
/**
 * SERVER DIAGNOSTIC SCRIPT
 * Upload this to /home/rsmmultilink/public_html/server-diagnostic.php
 * Access via: https://rsmmultilink.com/server-diagnostic.php?key=rsm123
 * 
 * DELETE THIS FILE AFTER FIXING ISSUES!
 */

// Security check
if (!isset($_GET['key']) || $_GET['key'] !== 'rsm123') {
    http_response_code(403);
    die("Access denied");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Server Diagnostic Report</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; border-left: 4px solid #007bff; padding-left: 10px; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px solid #ddd; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        table td { padding: 8px; border: 1px solid #ddd; }
        table td:first-child { font-weight: bold; width: 250px; background: #f8f9fa; }
        .status-ok { background: #d4edda; }
        .status-error { background: #f8d7da; }
        .status-warning { background: #fff3cd; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔍 Server Diagnostic Report</h1>
    <p><strong>Generated:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>

    <h2>1. PHP Configuration</h2>
    <table>
        <tr class="<?php echo (PHP_VERSION_ID >= 80100) ? 'status-ok' : 'status-error'; ?>">
            <td>PHP Version</td>
            <td><?php echo PHP_VERSION; ?> <?php echo (PHP_VERSION_ID >= 80100) ? '✅' : '❌ Need 8.1+'; ?></td>
        </tr>
        <tr>
            <td>PHP SAPI</td>
            <td><?php echo php_sapi_name(); ?></td>
        </tr>
        <tr>
            <td>Memory Limit</td>
            <td><?php echo ini_get('memory_limit'); ?></td>
        </tr>
        <tr>
            <td>Upload Max Filesize</td>
            <td><?php echo ini_get('upload_max_filesize'); ?></td>
        </tr>
        <tr>
            <td>Post Max Size</td>
            <td><?php echo ini_get('post_max_size'); ?></td>
        </tr>
        <tr>
            <td>Max Execution Time</td>
            <td><?php echo ini_get('max_execution_time'); ?>s</td>
        </tr>
        <tr>
            <td>Display Errors</td>
            <td><?php echo ini_get('display_errors') ? 'ON ⚠️' : 'OFF ✅'; ?></td>
        </tr>
        <tr>
            <td>Error Reporting</td>
            <td><?php echo error_reporting(); ?></td>
        </tr>
    </table>

    <h2>2. Required PHP Extensions</h2>
    <table>
        <?php
        $requiredExtensions = [
            'pdo_mysql' => 'Database connectivity',
            'mbstring' => 'String handling',
            'openssl' => 'Encryption',
            'curl' => 'HTTP requests',
            'zip' => 'File compression',
            'fileinfo' => 'File type detection',
            'json' => 'JSON parsing',
            'tokenizer' => 'Laravel parsing',
            'xml' => 'XML handling',
            'gd' => 'Image manipulation',
            'session' => 'Session management',
        ];
        
        foreach ($requiredExtensions as $ext => $desc) {
            $loaded = extension_loaded($ext);
            $class = $loaded ? 'status-ok' : 'status-error';
            $status = $loaded ? '✅ Loaded' : '❌ Missing';
            echo "<tr class='$class'><td>$ext <small>($desc)</small></td><td>$status</td></tr>";
        }
        ?>
    </table>

    <h2>3. Laravel Files & Directories</h2>
    <table>
        <?php
        $paths = [
            '.env' => 'Environment config',
            'public/index.php' => 'Entry point',
            'vendor/autoload.php' => 'Composer autoload',
            'bootstrap/cache' => 'Bootstrap cache',
            'storage/framework/cache' => 'Framework cache',
            'storage/framework/sessions' => 'Sessions',
            'storage/framework/views' => 'Compiled views',
            'storage/logs' => 'Log files',
            'storage/app/public' => 'Public uploads',
            'public/storage' => 'Storage symlink',
        ];
        
        foreach ($paths as $path => $desc) {
            $fullPath = __DIR__ . '/' . $path;
            $exists = file_exists($fullPath);
            $isLink = is_link($fullPath);
            $writable = is_writable($fullPath);
            
            $class = $exists ? 'status-ok' : 'status-error';
            $status = $exists ? '✅ Exists' : '❌ Missing';
            
            if ($exists && !$writable && is_dir($fullPath)) {
                $class = 'status-warning';
                $status .= ' ⚠️ Not writable';
            }
            
            if ($isLink) {
                $target = readlink($fullPath);
                $status .= " → $target";
            }
            
            echo "<tr class='$class'><td>$path <small>($desc)</small></td><td>$status</td></tr>";
        }
        ?>
    </table>

    <h2>4. File Permissions</h2>
    <table>
        <?php
        $checkPerms = [
            'storage',
            'bootstrap/cache',
            '.env',
        ];
        
        foreach ($checkPerms as $path) {
            $fullPath = __DIR__ . '/' . $path;
            if (file_exists($fullPath)) {
                $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
                $writable = is_writable($fullPath);
                $class = $writable ? 'status-ok' : 'status-error';
                $status = "$perms " . ($writable ? '✅ Writable' : '❌ Not writable');
                echo "<tr class='$class'><td>$path</td><td>$status</td></tr>";
            }
        }
        ?>
    </table>

    <h2>5. Environment File (.env)</h2>
    <?php
    $envPath = __DIR__ . '/.env';
    if (file_exists($envPath)) {
        echo "<p class='success'>✅ .env file exists</p>";
        $envContent = file_get_contents($envPath);
        $envLines = explode("\n", $envContent);
        
        echo "<table>";
        $sensitiveKeys = ['PASSWORD', 'KEY', 'SECRET', 'TOKEN'];
        foreach ($envLines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) continue;
            
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                
                // Mask sensitive values
                $isSensitive = false;
                foreach ($sensitiveKeys as $sensitiveKey) {
                    if (stripos($key, $sensitiveKey) !== false) {
                        $isSensitive = true;
                        break;
                    }
                }
                
                if ($isSensitive && !empty($value)) {
                    $value = '***HIDDEN***';
                }
                
                $class = empty($value) ? 'status-warning' : '';
                $status = empty($value) ? '⚠️ Empty' : $value;
                
                echo "<tr class='$class'><td>$key</td><td>$status</td></tr>";
            }
        }
        echo "</table>";
    } else {
        echo "<p class='error'>❌ .env file not found!</p>";
    }
    ?>

    <h2>6. Database Connection Test</h2>
    <?php
    if (file_exists($envPath)) {
        $env = parse_ini_file($envPath);
        $dbHost = $env['DB_HOST'] ?? '';
        $dbName = $env['DB_DATABASE'] ?? '';
        $dbUser = $env['DB_USERNAME'] ?? '';
        $dbPass = $env['DB_PASSWORD'] ?? '';
        
        try {
            $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
            echo "<p class='success'>✅ Database connection successful!</p>";
            echo "<table>";
            echo "<tr><td>Host</td><td>$dbHost</td></tr>";
            echo "<tr><td>Database</td><td>$dbName</td></tr>";
            echo "<tr><td>Username</td><td>$dbUser</td></tr>";
            echo "</table>";
            
            // Check tables
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "<p><strong>Tables found:</strong> " . count($tables) . "</p>";
            if (count($tables) > 0) {
                echo "<pre>" . implode(", ", $tables) . "</pre>";
            }
        } catch (PDOException $e) {
            echo "<p class='error'>❌ Database connection failed!</p>";
            echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
        }
    }
    ?>

    <h2>7. Laravel Error Logs (Last 50 lines)</h2>
    <?php
    $logPath = __DIR__ . '/storage/logs/laravel.log';
    if (file_exists($logPath)) {
        $logContent = file($logPath);
        $lastLines = array_slice($logContent, -50);
        echo "<pre>" . htmlspecialchars(implode("", $lastLines)) . "</pre>";
    } else {
        echo "<p>No log file found</p>";
    }
    ?>

    <h2>8. Server Information</h2>
    <table>
        <tr><td>Server Software</td><td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></td></tr>
        <tr><td>Document Root</td><td><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown'; ?></td></tr>
        <tr><td>Server Name</td><td><?php echo $_SERVER['SERVER_NAME'] ?? 'Unknown'; ?></td></tr>
        <tr><td>Server IP</td><td><?php echo $_SERVER['SERVER_ADDR'] ?? 'Unknown'; ?></td></tr>
        <tr><td>Script Filename</td><td><?php echo __FILE__; ?></td></tr>
    </table>

    <h2>9. Loaded PHP Modules</h2>
    <pre><?php print_r(get_loaded_extensions()); ?></pre>

    <hr>
    <p><strong>⚠️ SECURITY WARNING:</strong> DELETE THIS FILE AFTER DIAGNOSIS!</p>
    <pre>rm /home/rsmmultilink/public_html/server-diagnostic.php</pre>
</div>
</body>
</html>
