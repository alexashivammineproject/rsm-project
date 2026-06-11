<?php
/**
 * Emergency Website Fix Script
 * This will help fix the white screen issue
 * 
 * Access: https://www.rsmmultilink.com/fix-website.php?action=check
 */

// Security
$allowed_actions = ['check', 'autoload', 'permissions'];
$action = $_GET['action'] ?? 'check';

if (!in_array($action, $allowed_actions)) {
    die('Invalid action');
}

echo "<h1>Website Fix Script</h1><hr>";

if ($action === 'check') {
    echo "<h2>Diagnostic Check</h2>";
    
    echo "<h3>1. Critical Files Status:</h3>";
    $files = [
        'vendor/autoload.php' => 'Composer Autoload',
        'bootstrap/app.php' => 'Laravel Bootstrap',
        'app/Providers/AppServiceProvider.php' => 'Service Provider',
        'app/helpers.php' => 'Custom Helpers',
        '.env' => 'Environment Config'
    ];
    
    foreach ($files as $file => $name) {
        $exists = file_exists(__DIR__ . '/' . $file);
        echo "$name ($file): " . ($exists ? "✓ EXISTS" : "✗ MISSING") . "<br>";
    }
    
    echo "<h3>2. PHP Extensions:</h3>";
    $extensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'json'];
    foreach ($extensions as $ext) {
        echo "$ext: " . (extension_loaded($ext) ? "✓ Loaded" : "✗ Missing") . "<br>";
    }
    
    echo "<h3>3. Storage Permissions:</h3>";
    $dirs = ['storage', 'storage/logs', 'storage/framework', 'bootstrap/cache'];
    foreach ($dirs as $dir) {
        $path = __DIR__ . '/' . $dir;
        $writable = is_writable($path);
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        echo "$dir: $perms " . ($writable ? "✓ Writable" : "✗ Not Writable") . "<br>";
    }
    
    echo "<h3>4. Try to Bootstrap Laravel:</h3>";
    try {
        if (file_exists(__DIR__ . '/vendor/autoload.php')) {
            require __DIR__ . '/vendor/autoload.php';
            echo "✓ Autoload loaded<br>";
            
            $app = require_once __DIR__ . '/bootstrap/app.php';
            echo "✓ Laravel bootstrapped successfully<br>";
            echo "Laravel Version: " . $app->version() . "<br>";
            
            if (function_exists('safe_storage_url')) {
                echo "✓ Custom helpers loaded<br>";
            } else {
                echo "✗ Custom helpers NOT loaded<br>";
            }
        } else {
            echo "<strong style='color:red;'>✗ CRITICAL: vendor/autoload.php NOT FOUND</strong><br>";
            echo "<p style='color:red;'>This is the main problem! Without vendor folder, Laravel cannot work.</p>";
            echo "<p><strong>SOLUTION:</strong></p>";
            echo "<ol>";
            echo "<li>Option 1: Install composer on server and run: composer install</li>";
            echo "<li>Option 2: Upload vendor folder from local machine</li>";
            echo "<li>Option 3: Use manual autoload fix (click link below)</li>";
            echo "</ol>";
            echo "<p><a href='?action=autoload' style='font-size:18px; color:blue;'>→ Try Manual Autoload Fix</a></p>";
        }
    } catch (Exception $e) {
        echo "<strong style='color:red;'>✗ ERROR: " . $e->getMessage() . "</strong><br>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    
    echo "<hr>";
    echo "<p><strong>Actions:</strong></p>";
    echo "<a href='?action=check'>Check Again</a> | ";
    echo "<a href='?action=autoload'>Try Autoload Fix</a> | ";
    echo "<a href='?action=permissions'>Fix Permissions</a>";
}

elseif ($action === 'autoload') {
    echo "<h2>Manual Autoload Fix</h2>";
    echo "<p>Creating minimal autoload file...</p>";
    
    $autoload_content = <<<'PHP'
<?php
/**
 * Emergency Minimal Autoload
 * Created by fix-website.php
 */

// Register PSR-4 autoloader
spl_autoload_register(function ($class) {
    // App namespace
    if (strpos($class, 'App\\') === 0) {
        $file = __DIR__ . '/../app/' . str_replace(['App\\', '\\'], ['', '/'], $class) . '.php';
        if (file_exists($file)) {
            require $file;
            return true;
        }
    }
    return false;
});

// Load Laravel vendor packages manually (critical ones only)
$vendorDir = __DIR__ . '/../vendor';

// Load Composer's autoloader if it exists
if (file_exists($vendorDir . '/composer/autoload_real.php')) {
    require $vendorDir . '/composer/autoload_real.php';
    return ComposerAutoloaderInit::getLoader();
}

// If vendor doesn't exist, show error
if (!is_dir($vendorDir)) {
    die('<h1>Error: Vendor folder missing!</h1>
    <p>Laravel cannot run without the vendor folder.</p>
    <p><strong>Solution:</strong></p>
    <ol>
        <li>Upload vendor folder from your local machine to server</li>
        <li>Or install composer on server and run: composer install</li>
    </ol>
    ');
}

echo "Warning: Using emergency minimal autoload. Upload proper vendor folder ASAP!";
PHP;

    // Try to write to vendor/autoload.php
    $vendor_dir = __DIR__ . '/vendor';
    if (!is_dir($vendor_dir)) {
        mkdir($vendor_dir, 0755, true);
    }
    
    $autoload_file = $vendor_dir . '/autoload.php';
    if (file_put_contents($autoload_file, $autoload_content)) {
        echo "✓ Created emergency autoload at: $autoload_file<br>";
        echo "<p style='color:orange;'><strong>WARNING:</strong> This is a temporary fix. You MUST upload the proper vendor folder!</p>";
        echo "<p><a href='/'>Try visiting homepage now</a></p>";
    } else {
        echo "✗ Failed to create autoload file. Check permissions!<br>";
    }
}

elseif ($action === 'permissions') {
    echo "<h2>Fixing Permissions</h2>";
    
    $dirs = [
        'storage',
        'storage/app',
        'storage/app/public',
        'storage/framework',
        'storage/framework/cache',
        'storage/framework/sessions',
        'storage/framework/views',
        'storage/logs',
        'bootstrap/cache'
    ];
    
    foreach ($dirs as $dir) {
        $path = __DIR__ . '/' . $dir;
        if (is_dir($path)) {
            chmod($path, 0755);
            echo "✓ Fixed: $dir<br>";
        } else {
            if (mkdir($path, 0755, true)) {
                echo "✓ Created: $dir<br>";
            } else {
                echo "✗ Failed: $dir<br>";
            }
        }
    }
    
    echo "<p>Permissions updated!</p>";
    echo "<p><a href='?action=check'>Check Status Again</a></p>";
}

echo "<hr><p><em>After fixing, delete this file for security.</em></p>";
?>
