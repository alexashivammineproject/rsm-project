<?php
/**
 * DIRECT DEPLOYMENT FIX
 * URL: https://rsmmultilink.com/deploy-fix.php?key=rsm123
 * 
 * This will manually download and extract GitHub ZIP properly
 */

set_time_limit(300);

// Security
if (!isset($_GET['key']) || $_GET['key'] !== 'rsm123') {
    die("Access denied");
}

header('Content-Type: text/html; charset=utf-8');
echo "<pre>";
echo "=== DEPLOYMENT FIX STARTED ===\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n\n";

$rootDir = __DIR__;
$zipUrl = "https://github.com/rsmmultilinkupdate-crypto/rsm-project/archive/refs/heads/main.zip";
$zipFile = $rootDir . "/deploy-temp.zip";
$extractTemp = $rootDir . "/deploy-temp/";

// Step 1: Download ZIP
echo "STEP 1: Downloading ZIP from GitHub...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $zipUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
$zipContent = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    die("✗ Failed to download ZIP (HTTP $httpCode)\n");
}

file_put_contents($zipFile, $zipContent);
echo "✓ Downloaded " . round(strlen($zipContent) / 1024 / 1024, 2) . " MB\n\n";

// Step 2: Extract ZIP
echo "STEP 2: Extracting ZIP...\n";
$zip = new ZipArchive;
if ($zip->open($zipFile) !== TRUE) {
    die("✗ Failed to open ZIP file\n");
}

// Create temp directory
if (!is_dir($extractTemp)) {
    mkdir($extractTemp, 0755, true);
}

$zip->extractTo($extractTemp);
$zip->close();
echo "✓ Extracted to temp folder\n\n";

// Step 3: Copy files
echo "STEP 3: Copying files...\n";
$sourceDir = $extractTemp . "rsm-project-main/";

if (!is_dir($sourceDir)) {
    die("✗ Source directory not found: $sourceDir\n");
}

// Copy using shell
$output = [];
exec("cp -rf {$sourceDir}* {$rootDir}/ 2>&1", $output, $return1);
echo "Copy normal files: " . ($return1 === 0 ? "✓" : "✗") . "\n";
if (!empty($output)) echo implode("\n", $output) . "\n";

// Copy hidden files
$output2 = [];
exec("cp -rf {$sourceDir}.* {$rootDir}/ 2>&1", $output2, $return2);
echo "Copy hidden files: " . ($return2 === 0 ? "✓" : "✗") . "\n";
if (!empty($output2)) echo implode("\n", $output2) . "\n";

echo "\n";

// Step 4: Cleanup
echo "STEP 4: Cleaning up...\n";
exec("rm -rf " . escapeshellarg($extractTemp) . " 2>&1");
@unlink($zipFile);
echo "✓ Cleaned temp files\n\n";

// Step 5: Delete malware
echo "STEP 5: Deleting malware...\n";
$malware = ['filefuns.php', 'goods.php', 'shell.php', 'c99.php'];
$deleted = 0;
foreach ($malware as $file) {
    if (file_exists($rootDir . '/' . $file)) {
        if (@unlink($rootDir . '/' . $file)) {
            echo "✓ Deleted: $file\n";
            $deleted++;
        }
    }
}
echo "Deleted $deleted malware files\n\n";

// Step 6: Clear Laravel cache
echo "STEP 6: Clearing Laravel caches...\n";
chdir($rootDir);
exec("php artisan config:clear 2>&1", $out1);
exec("php artisan cache:clear 2>&1", $out2);
exec("php artisan view:clear 2>&1", $out3);
echo "✓ Caches cleared\n\n";

// Step 7: Verify files
echo "STEP 7: Verifying deployment...\n";
$testFiles = ['ping.php', 'check.php', 'emergency-fix.php', 'public/index.php', 'server.php'];
foreach ($testFiles as $file) {
    $exists = file_exists($rootDir . '/' . $file);
    echo ($exists ? "✓" : "✗") . " $file\n";
}

echo "\n=== DEPLOYMENT COMPLETED ===\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n";
echo "\nTest URLs:\n";
echo "- https://rsmmultilink.com/ping.php\n";
echo "- https://www.rsmmultilink.com\n";
echo "- https://www.rsmmultilink.com/check.php\n";
echo "- https://www.rsmmultilink.com/emergency-fix.php\n";
echo "\n⚠️ DELETE THIS FILE: deploy-fix.php\n";
echo "</pre>";
?>
