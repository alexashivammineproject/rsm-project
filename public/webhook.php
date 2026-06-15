<?php
/**
 * RSM Multilink - GitHub Auto Deploy Webhook
 * URL: https://rsmmultilink.com/webhook.php?key=rsm123
 */

// Security key check
if (!isset($_GET['key']) || $_GET['key'] !== 'rsm123') {
    http_response_code(403);
    die("Access denied");
}

$zipUrl     = "https://github.com/rsmmultilinkupdate-crypto/rsm-project/archive/refs/heads/main.zip";
$zipFile    = "/home/rsmmultilink/public_html/project_deploy.zip";
$extractPath = "/home/rsmmultilink/public_html/";
$folderName  = "rsm-project-main";

echo "<pre>";
echo "=== RSM Deploy Started: " . date('Y-m-d H:i:s') . " ===\n\n";

// STEP 1: Download ZIP using cURL (works even when allow_url_fopen=0)
echo "1. Downloading ZIP from GitHub...\n";
$ch = curl_init($zipUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 120);
curl_setopt($ch, CURLOPT_USERAGENT, 'RSM-Deploy/1.0');
$zipData = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError || $httpCode !== 200 || empty($zipData)) {
    die("ERROR: Download failed. HTTP: $httpCode, cURL: $curlError\n");
}

file_put_contents($zipFile, $zipData);
echo "   Downloaded: " . round(strlen($zipData)/1024) . " KB\n";

// STEP 2: Extract ZIP
echo "2. Extracting ZIP...\n";
$zip = new ZipArchive;
if ($zip->open($zipFile) !== TRUE) {
    unlink($zipFile);
    die("ERROR: Cannot open ZIP file\n");
}
$zip->extractTo($extractPath);
$zip->close();
echo "   Extracted to: $extractPath\n";

// STEP 3: Backup current .env before overwriting
echo "3. Preserving .env file...\n";
$envFile    = $extractPath . ".env";
$envBackup  = $extractPath . ".env.backup_deploy";
if (file_exists($envFile)) {
    copy($envFile, $envBackup);
    echo "   .env backed up\n";
}

// STEP 4: Move files from extracted folder to root
echo "4. Moving files to root...\n";
$sourceDir = $extractPath . $folderName;
if (!is_dir($sourceDir)) {
    unlink($zipFile);
    die("ERROR: Extracted folder '$folderName' not found\n");
}

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($files as $file) {
    $destPath = $extractPath . substr($file->getPathname(), strlen($sourceDir) + 1);
    if ($file->isDir()) {
        if (!is_dir($destPath)) mkdir($destPath, 0755, true);
    } else {
        // Skip .env - preserve existing live .env
        if (basename($file->getPathname()) === '.env') continue;
        copy($file->getPathname(), $destPath);
    }
}
echo "   Files moved\n";

// STEP 5: Restore .env backup
if (file_exists($envBackup)) {
    copy($envBackup, $envFile);
    unlink($envBackup);
    echo "   .env restored\n";
}

// STEP 6: Cleanup
echo "5. Cleaning up...\n";
unlink($zipFile);

// Remove extracted folder
$it = new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS);
$files2 = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
foreach ($files2 as $file) {
    $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
}
rmdir($sourceDir);
echo "   Cleanup done\n";

// STEP 7: Run artisan commands
echo "6. Running artisan commands...\n";
$artisanPath = $extractPath . "artisan";
$phpBin = PHP_BINARY ?: 'php';

$commands = [
    'config:clear',
    'cache:clear',
    'view:clear',
    'route:clear',
    'config:cache',
    'route:cache',
    'view:cache',
    'migrate --force',
];

foreach ($commands as $cmd) {
    $output = shell_exec("$phpBin $artisanPath $cmd 2>&1");
    echo "   php artisan $cmd: " . trim($output) . "\n";
}

echo "\n=== Deploy Completed: " . date('Y-m-d H:i:s') . " ===\n";
echo "</pre>";
