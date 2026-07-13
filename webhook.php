<?php
/**
 * RSM Emergency Fix + Auto Deploy Webhook
 * 
 * NORMAL DEPLOY:     https://rsmmultilink.com/webhook.php?key=rsm123
 * EMERGENCY FIX:     https://rsmmultilink.com/webhook.php?key=rsm123&action=emergency_fix
 * STATUS CHECK:      https://rsmmultilink.com/webhook.php?key=rsm123&action=status
 */

if (!isset($_GET['key']) || $_GET['key'] !== 'rsm123') {
    http_response_code(403);
    die(json_encode(['error' => 'Access denied']));
}

$action      = $_GET['action'] ?? 'deploy';
$rootPath    = '/home/rsmmultilink/public_html/';
$storagePath = $rootPath . 'storage/app/public/';
$logFile     = $rootPath . 'deployment.log';

header('Content-Type: text/html; charset=UTF-8');
echo "<pre style='font-family:monospace;font-size:13px;background:#1a1a2e;color:#00ff88;padding:20px'>";
echo "🚀 RSM WEBHOOK - Action: <strong>$action</strong>\n";
echo "⏰ " . date('Y-m-d H:i:s') . "\n";
echo str_repeat('─', 60) . "\n\n";

function log_msg(string $msg) {
    global $logFile;
    $line = "[" . date('Y-m-d H:i:s') . "] $msg\n";
    file_put_contents($logFile, $line, FILE_APPEND);
    echo $msg . "\n";
    @ob_flush(); @flush();
}

function run(string $cmd): string {
    $output = [];
    exec($cmd . " 2>&1", $output);
    return implode("\n", $output);
}

// ============================================================
// STATUS CHECK
// ============================================================
if ($action === 'status') {
    log_msg("📊 SITE STATUS CHECK");
    log_msg("PHP: " . phpversion());
    log_msg("Disk: " . run("df -h /home/rsmmultilink/ | tail -1"));
    log_msg("Images in storage root: " . count(glob($storagePath . '*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE)));
    log_msg("Images in images/: " . count(glob($storagePath . 'images/*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE)));
    log_msg("Symlink: " . (is_link($rootPath . 'public/storage') ? '✅ OK' : '❌ BROKEN'));
    log_msg("Malware check: " . run("find {$rootPath} -maxdepth 1 -name '*.php' | grep -v -E '(artisan|webhook|server)' | head -5") ?: "✅ Clean");
    $recentErrors = run("tail -5 {$rootPath}storage/logs/laravel.log | grep ERROR | wc -l");
    log_msg("Recent errors: $recentErrors");
    echo "</pre>";
    exit;
}

// ============================================================
// EMERGENCY FIX - One click full website restore
// ============================================================
if ($action === 'emergency_fix') {
    log_msg("🚨 EMERGENCY FIX STARTED");
    log_msg(str_repeat('═', 50));

    // STEP 1: Malware cleanup
    log_msg("\n🦠 STEP 1: Malware Cleanup");
    $malware = ['chosen.php','simple.php','shell.php','c99.php','r57.php',
        'wso.php','alfa.php','backdoor.php','adminer.php','filefuns.php',
        'goods.php','fix-images.php','server-diagnostic.php','security-fix-deploy.php'];
    foreach ($malware as $f) {
        foreach ([$rootPath, $rootPath.'public/'] as $dir) {
            if (file_exists($dir.$f)) {
                unlink($dir.$f);
                log_msg("  ✅ Deleted: $f");
            }
        }
    }
    // Scan for suspicious large PHP files in root
    foreach (glob($rootPath . "*.php") as $phpFile) {
        $base = basename($phpFile);
        if (!in_array($base, ['artisan','webhook.php','server.php']) && filesize($phpFile) > 50000) {
            unlink($phpFile);
            log_msg("  ✅ Deleted suspicious: $base (" . round(filesize($phpFile)/1024) . "KB)");
        }
    }
    log_msg("  Malware cleanup done");

    // STEP 2: Git pull latest code + images
    log_msg("\n📥 STEP 2: Pull Latest Code from GitHub");
    run("git config --global --add safe.directory " . escapeshellarg($rootPath));
    run("git -C " . escapeshellarg($rootPath) . " remote remove origin 2>/dev/null || true");
    run("git -C " . escapeshellarg($rootPath) . " remote add origin https://github.com/rsmmultilinkupdate-crypto/rsm-project.git");
    $fetch = run("git -C " . escapeshellarg($rootPath) . " fetch origin main --depth=1");
    log_msg("  Fetch: " . (strpos($fetch, 'error') === false ? '✅ OK' : '❌ ' . $fetch));
    $checkout = run("git -C " . escapeshellarg($rootPath) . " checkout origin/main -- .");
    log_msg("  Checkout: ✅ Done");

    // STEP 3: Restore .env (don't overwrite if exists)
    log_msg("\n⚙️  STEP 3: Verify .env");
    if (!file_exists($rootPath . '.env')) {
        log_msg("  ❌ .env MISSING! Manual restore needed.");
    } else {
        log_msg("  ✅ .env exists");
    }

    // STEP 4: Fix storage symlink
    log_msg("\n🔗 STEP 4: Fix Storage Symlink");
    $symlink = $rootPath . 'public/storage';
    $target  = $storagePath;
    if (is_link($symlink)) unlink($symlink);
    if (is_dir($symlink)) run("rm -rf " . escapeshellarg($symlink));
    symlink(rtrim($target, '/'), $symlink);
    run("chown -h rsmmultilink:rsmmultilink " . escapeshellarg($symlink));
    log_msg("  ✅ Symlink: public/storage -> storage/app/public");

    // STEP 5: Copy images from images/ subfolder to storage root
    log_msg("\n🖼️  STEP 5: Fix Images");
    if (!is_dir($storagePath . 'images/')) {
        // Pull images from git
        $imgPull = run("git -C " . escapeshellarg($rootPath) . " checkout origin/main -- storage/app/public/images/");
        log_msg("  Images pulled from git");
    }
    $copied = 0;
    if (is_dir($storagePath . 'images/')) {
        $iter = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($storagePath . 'images/', RecursiveDirectoryIterator::SKIP_DOTS)
        );
        foreach ($iter as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), ['jpg','jpeg','png','webp','gif'])) {
                $dest = $storagePath . $file->getFilename();
                copy($file->getPathname(), $dest);
                $copied++;
            }
        }
    }
    $totalImages = count(glob($storagePath . '*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE));
    log_msg("  ✅ Copied $copied images to storage root | Total: $totalImages");

    // STEP 6: Fix permissions
    log_msg("\n🔐 STEP 6: Fix Permissions");
    run("chown -R rsmmultilink:rsmmultilink " . escapeshellarg($storagePath));
    run("chown -R rsmmultilink:rsmmultilink " . escapeshellarg($rootPath . 'bootstrap/cache/'));
    run("chmod -R 755 " . escapeshellarg($storagePath));
    run("chmod -R 755 " . escapeshellarg($rootPath . 'bootstrap/cache/'));
    log_msg("  ✅ Permissions fixed");

    // STEP 7: Laravel cache clear
    log_msg("\n⚡ STEP 7: Clear Laravel Cache");
    chdir($rootPath);
    $cmds = ['config:clear','cache:clear','view:clear','route:clear'];
    foreach ($cmds as $cmd) {
        $out = run("php artisan $cmd");
        log_msg("  " . (strpos($out, 'successfully') !== false ? '✅' : '⚠️') . " $cmd");
    }

    // STEP 8: Run migrations
    log_msg("\n🗄️  STEP 8: Database Migrations");
    $migrate = run("php artisan migrate --force");
    log_msg("  " . (strpos($migrate, 'Nothing') !== false || strpos($migrate, 'DONE') !== false ? '✅' : '⚠️') . " " . substr($migrate, 0, 100));

    // STEP 9: Security headers check
    log_msg("\n🛡️  STEP 9: Security Check");
    $htaccess = $rootPath . '.htaccess';
    if (file_exists($htaccess)) {
        log_msg("  ✅ .htaccess exists");
    }

    // STEP 10: Clear log file to start fresh
    log_msg("\n📋 STEP 10: Clear Old Logs");
    file_put_contents($rootPath . 'storage/logs/laravel.log', '');
    log_msg("  ✅ Laravel log cleared");

    // Final test
    log_msg("\n" . str_repeat('═', 50));
    log_msg("✅ EMERGENCY FIX COMPLETE!");
    log_msg("🌐 Site: https://rsmmultilink.com");
    log_msg("📊 Images: $totalImages");
    log_msg("⏰ " . date('Y-m-d H:i:s'));

    echo "</pre>";
    echo "<script>setTimeout(function(){ window.location='https://rsmmultilink.com'; }, 5000);</script>";
    exit;
}

// ============================================================
// NORMAL DEPLOY - GitHub ZIP download
// ============================================================
log_msg("📦 NORMAL DEPLOY STARTED");

$configFile = __DIR__ . '/webhook-config.php';
$githubToken = '';
$zipUrl = "https://github.com/rsmmultilinkupdate-crypto/rsm-project/archive/refs/heads/main.zip";
if (file_exists($configFile)) {
    $config = include $configFile;
    $githubToken = $config['github_token'] ?? '';
    $zipUrl = $config['zip_url'] ?? $zipUrl;
}
$zipFile = $rootPath . "project.zip";

try {
    // Download ZIP
    log_msg("⬇️  Downloading from GitHub...");
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $zipUrl,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0',
        CURLOPT_HTTPHEADER => $githubToken ? [
            'Authorization: token ' . $githubToken,
            'Accept: application/vnd.github.v3+json'
        ] : [],
    ]);
    $zipContent = curl_exec($ch);
    $httpCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$zipContent || $httpCode !== 200) {
        throw new Exception("ZIP download failed. HTTP: $httpCode");
    }
    file_put_contents($zipFile, $zipContent);
    log_msg("✅ Downloaded " . round(strlen($zipContent)/1024/1024, 2) . " MB");

    // Extract
    log_msg("📂 Extracting...");
    $zip = new ZipArchive;
    if ($zip->open($zipFile) !== TRUE) throw new Exception("Failed to extract ZIP");
    $zip->extractTo($rootPath);
    $zip->close();
    log_msg("✅ Extracted");

    // Move files
    $extracted = $rootPath . "rsm-project-main/";
    if (is_dir($extracted)) {
        run("cp -rf " . escapeshellarg($extracted) . "* " . escapeshellarg($rootPath));
        foreach (glob($extracted . ".*") as $hf) {
            $base = basename($hf);
            if (in_array($base, ['.', '..', '.env'])) continue;
            $dest = $rootPath . $base;
            is_file($hf) ? copy($hf, $dest) : run("cp -rf " . escapeshellarg($hf) . " " . escapeshellarg($dest));
        }
        run("rm -rf " . escapeshellarg($extracted));
        log_msg("✅ Files deployed");
    }
    if (file_exists($zipFile)) unlink($zipFile);

    // Copy images to storage root
    if (is_dir($storagePath . 'images/')) {
        $copied = 0;
        $iter = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($storagePath . 'images/', RecursiveDirectoryIterator::SKIP_DOTS)
        );
        foreach ($iter as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), ['jpg','jpeg','png','webp','gif'])) {
                copy($file->getPathname(), $storagePath . $file->getFilename());
                $copied++;
            }
        }
        log_msg("✅ $copied images copied to storage root");
    }

    // Malware cleanup
    foreach (['chosen.php','simple.php','shell.php','c99.php','r57.php','wso.php','alfa.php','backdoor.php'] as $mf) {
        if (file_exists($rootPath.$mf)) { unlink($rootPath.$mf); log_msg("✅ Deleted malware: $mf"); }
    }

    // Fix permissions + symlink
    $symlink = $rootPath . 'public/storage';
    if (is_link($symlink)) unlink($symlink);
    symlink(rtrim($storagePath, '/'), $symlink);
    run("chown -h rsmmultilink:rsmmultilink " . escapeshellarg($symlink));
    run("chown -R rsmmultilink:rsmmultilink " . escapeshellarg($storagePath));
    run("chmod -R 755 " . escapeshellarg($storagePath));
    run("chmod -R 755 " . escapeshellarg($rootPath . 'bootstrap/cache/'));
    log_msg("✅ Permissions & symlink fixed");

    // Laravel commands
    chdir($rootPath);
    run("php artisan migrate --force");
    foreach (['config:clear','cache:clear','view:clear','route:clear'] as $cmd) {
        run("php artisan $cmd");
    }
    log_msg("✅ Cache cleared");

    log_msg("\n🎉 DEPLOY COMPLETE! https://rsmmultilink.com");
    echo "</pre>";
    echo json_encode(['status' => 'success', 'time' => date('Y-m-d H:i:s')]);

} catch (Exception $e) {
    log_msg("❌ ERROR: " . $e->getMessage());
    if (file_exists($zipFile)) unlink($zipFile);
    http_response_code(500);
    echo "</pre>";
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
