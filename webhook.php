<?php
/**
 * GitHub Auto-Deployment + Emergency Fix Webhook
 * URL: https://rsmmultilink.com/webhook.php?key=rsm123
 * Emergency Fix: https://rsmmultilink.com/webhook.php?key=rsm123&action=emergency_fix
 */

if (!isset($_GET['key']) || $_GET['key'] !== 'rsm123') {
    http_response_code(403);
    die("Access denied");
}

$action = $_GET['action'] ?? 'deploy';
$extractPath = "/home/rsmmultilink/public_html/";
$logFile = $extractPath . "deployment.log";

function logMessage($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
    echo "[$timestamp] $message\n";
}

ob_implicit_flush(true);
echo "<pre style='font-family:monospace'>";
logMessage("=== ACTION: $action ===");

// ============================================================
// EMERGENCY FIX - One click sab kuch fix
// ============================================================
if ($action === 'emergency_fix') {
    logMessage("🚨 EMERGENCY FIX STARTED");

    // Step 1: Malware files delete karo
    logMessage("--- Step 1: Malware Cleanup ---");
    $malwarePatterns = ['chosen.php','simple.php','shell.php','c99.php','r57.php','wso.php',
        'alfa.php','backdoor.php','adminer.php','filefuns.php','goods.php'];
    foreach ($malwarePatterns as $f) {
        $path = $extractPath . $f;
        if (file_exists($path)) {
            unlink($path);
            logMessage("✅ Deleted malware: $f");
        }
    }
    // Scan for suspicious large PHP files in root
    foreach (glob($extractPath . "*.php") as $phpFile) {
        $base = basename($phpFile);
        $allowed = ['artisan','webhook.php','server.php'];
        if (!in_array($base, $allowed) && filesize($phpFile) > 50000) {
            unlink($phpFile);
            logMessage("✅ Deleted suspicious large file: $base");
        }
    }

    // Step 2: Git pull latest code + images
    logMessage("--- Step 2: Git Pull Latest Code ---");
    exec("cd " . escapeshellarg($extractPath) . " && git config --global --add safe.directory " . escapeshellarg($extractPath) . " 2>&1", $o); 
    exec("cd " . escapeshellarg($extractPath) . " && git fetch origin main --depth=1 2>&1", $o, $r);
    logMessage("Git fetch: " . implode(' ', $o));
    exec("cd " . escapeshellarg($extractPath) . " && git checkout origin/main -- storage/app/public/images/ 2>&1", $o2, $r2);
    logMessage("Git checkout images: " . implode(' ', $o2) . " (exit: $r2)");
    
    // Step 3: Copy images from images/ to root (non-interactive)
    logMessage("--- Step 3: Copy Images to Storage Root ---");
    $storageRoot = $extractPath . 'storage/app/public/';
    $imagesDir   = $storageRoot . 'images/';
    if (is_dir($imagesDir)) {
        $copied = 0;
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($imagesDir, RecursiveDirectoryIterator::SKIP_DOTS));
        foreach ($files as $file) {
            if ($file->isFile()) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
                    $dest = $storageRoot . $file->getFilename();
                    copy($file->getPathname(), $dest);
                    $copied++;
                }
            }
        }
        logMessage("✅ Copied $copied images to storage root");
    }

    // Step 4: Fix storage symlink
    logMessage("--- Step 4: Fix Storage Symlink ---");
    $symlink = $extractPath . 'public/storage';
    $target  = $extractPath . 'storage/app/public';
    if (is_link($symlink)) {
        unlink($symlink);
    }
    symlink($target, $symlink);
    logMessage("✅ Symlink fixed: public/storage -> storage/app/public");

    // Step 5: Fix permissions
    logMessage("--- Step 5: Fix Permissions ---");
    exec("chown -R rsmmultilink:rsmmultilink " . escapeshellarg($extractPath . 'storage/') . " 2>&1");
    exec("chown -h rsmmultilink:rsmmultilink " . escapeshellarg($symlink) . " 2>&1");
    exec("chmod -R 755 " . escapeshellarg($extractPath . 'storage/') . " 2>&1");
    exec("chmod -R 755 " . escapeshellarg($extractPath . 'bootstrap/cache/') . " 2>&1");
    logMessage("✅ Permissions fixed");

    // Step 6: Laravel cache clear
    logMessage("--- Step 6: Clear Laravel Cache ---");
    chdir($extractPath);
    exec("php artisan config:clear 2>&1", $o3); logMessage("Config: " . implode(' ', $o3));
    exec("php artisan cache:clear 2>&1",  $o4); logMessage("Cache: "  . implode(' ', $o4));
    exec("php artisan view:clear 2>&1",   $o5); logMessage("Views: "  . implode(' ', $o5));
    exec("php artisan route:clear 2>&1",  $o6); logMessage("Routes: " . implode(' ', $o6));

    // Step 7: Test image URLs
    logMessage("--- Step 7: Verify Images ---");
    $testImages = [
        'fg7VxlSp0UMsUcLUY8yeqQgJsLdGgiLHSksGR2NO.jpeg',
        'Bum3TQbdPa4C4DPadFy2HcHflkNnQ2H5dhCeeIKW.jpeg',
    ];
    foreach ($testImages as $img) {
        $exists = file_exists($storageRoot . $img) ? '✅ EXISTS' : '❌ MISSING';
        logMessage("  $img: $exists");
    }
    $totalImages = count(glob($storageRoot . '*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE));
    logMessage("📊 Total images in storage root: $totalImages");

    // Step 8: Remove this diagnostic info for security 
    logMessage("--- Step 8: Security Cleanup ---");
    $extraFiles = ['security-fix-deploy.php', 'server-diagnostic.php', 'fix-images.php'];
    foreach ($extraFiles as $ef) {
        if (file_exists($extractPath . $ef)) {
            unlink($extractPath . $ef);
            logMessage("✅ Deleted: $ef");
        }
        if (file_exists($extractPath . 'public/' . $ef)) {
            unlink($extractPath . 'public/' . $ef);
            logMessage("✅ Deleted from public/: $ef");
        }
    }

    logMessage("🎉 EMERGENCY FIX COMPLETE! Website should be fully working now.");
    logMessage("Check: https://rsmmultilink.com");
    echo "</pre>";
    exit;
}

// ============================================================
// NORMAL DEPLOY
// ============================================================
$configFile = __DIR__ . '/webhook-config.php';
if (file_exists($configFile)) {
    $config = include $configFile;
    $githubToken = $config['github_token'] ?? '';
    $zipUrl = $config['zip_url'] ?? "https://github.com/rsmmultilinkupdate-crypto/rsm-project/archive/refs/heads/main.zip";
} else {
    $githubToken = '';
    $zipUrl = "https://github.com/rsmmultilinkupdate-crypto/rsm-project/archive/refs/heads/main.zip";
}

$zipFile = $extractPath . "project.zip";

try {
    logMessage("=== Deployment Started ===");

    // Download ZIP
    logMessage("Downloading ZIP from GitHub...");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $zipUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    if (!empty($githubToken)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: token ' . $githubToken,
            'Accept: application/vnd.github.v3+json'
        ]);
    }
    $zipContent = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($zipContent === false || $httpCode !== 200) {
        throw new Exception("Failed to download ZIP. HTTP: $httpCode, Error: $curlError");
    }
    file_put_contents($zipFile, $zipContent);
    logMessage("✅ ZIP downloaded (" . round(strlen($zipContent)/1024/1024, 2) . " MB)");

    // Extract ZIP
    logMessage("Extracting ZIP...");
    $zip = new ZipArchive;
    if ($zip->open($zipFile) === TRUE) {
        $zip->extractTo($extractPath);
        $zip->close();
        logMessage("✅ ZIP extracted");
    } else {
        throw new Exception("Failed to extract ZIP");
    }

    // Move files
    $extractedFolder = $extractPath . "rsm-project-main/";
    if (is_dir($extractedFolder)) {
        exec("cp -rf " . escapeshellarg($extractedFolder) . "* " . escapeshellarg($extractPath) . " 2>&1", $out);
        // Copy hidden files except .env
        foreach (glob($extractedFolder . ".*") as $hf) {
            $base = basename($hf);
            if (in_array($base, ['.', '..', '.env'])) continue;
            $dest = $extractPath . $base;
            if (is_file($hf)) copy($hf, $dest);
            elseif (is_dir($hf)) exec("cp -rf " . escapeshellarg($hf) . " " . escapeshellarg($dest) . " 2>&1");
        }
        exec("rm -rf " . escapeshellarg($extractedFolder) . " 2>&1");
        logMessage("✅ Files moved");
    }

    // Cleanup zip
    if (file_exists($zipFile)) unlink($zipFile);

    // Copy images from images/ subfolder to root (non-interactive)
    $storageRoot = $extractPath . 'storage/app/public/';
    $imagesDir   = $storageRoot . 'images/';
    if (is_dir($imagesDir)) {
        $copied = 0;
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($imagesDir, RecursiveDirectoryIterator::SKIP_DOTS));
        foreach ($files as $file) {
            if ($file->isFile()) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
                    copy($file->getPathname(), $storageRoot . $file->getFilename());
                    $copied++;
                }
            }
        }
        logMessage("✅ Copied $copied images to storage root");
    }

    // Malware cleanup
    $malwareFiles = ['chosen.php','simple.php','shell.php','c99.php','r57.php',
        'wso.php','alfa.php','backdoor.php','adminer.php','filefuns.php','goods.php'];
    foreach ($malwareFiles as $mf) {
        $fp = $extractPath . $mf;
        if (file_exists($fp)) { unlink($fp); logMessage("✅ Deleted malware: $mf"); }
    }

    // Fix permissions
    exec("chown -R rsmmultilink:rsmmultilink " . escapeshellarg($extractPath . 'storage/') . " 2>&1");
    exec("chmod -R 755 " . escapeshellarg($extractPath . 'storage/') . " 2>&1");
    exec("chmod -R 755 " . escapeshellarg($extractPath . 'bootstrap/cache/') . " 2>&1");
    logMessage("✅ Permissions fixed");

    // Laravel commands
    chdir($extractPath);
    exec("php artisan migrate --force 2>&1", $om); logMessage("Migrate: " . implode(' ', $om));
    exec("php artisan config:clear 2>&1",    $o1); logMessage("Config cleared");
    exec("php artisan cache:clear 2>&1",     $o2); logMessage("Cache cleared");
    exec("php artisan view:clear 2>&1",      $o3); logMessage("Views cleared");
    exec("php artisan route:clear 2>&1",     $o4); logMessage("Routes cleared");

    logMessage("=== ✅ Deployment Complete! ===");
    echo "</pre>";
    echo json_encode(['status' => 'success', 'message' => 'Deployment completed', 'timestamp' => date('Y-m-d H:i:s')]);

} catch (Exception $e) {
    logMessage("❌ ERROR: " . $e->getMessage());
    http_response_code(500);
    echo "</pre>";
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
