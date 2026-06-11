<?php
/**
 * GitHub Auto-Deployment Webhook
 * URL: https://rsmmultilink.com/webhook.php?key=rsm123
 */

// Security check
if (!isset($_GET['key']) || $_GET['key'] !== 'rsm123') {
    http_response_code(403);
    die("Access denied");
}

// Load configuration
$configFile = __DIR__ . '/webhook-config.php';
if (file_exists($configFile)) {
    $config = include $configFile;
    $githubToken = $config['github_token'] ?? '';
    $zipUrl = $config['zip_url'] ?? "https://github.com/rsmmultilinkupdate-crypto/rsm-project/archive/refs/heads/main.zip";
    $extractPath = $config['extract_path'] ?? "/home/rsmmultilink/public_html/";
} else {
    $githubToken = '';
    $zipUrl = "https://github.com/rsmmultilinkupdate-crypto/rsm-project/archive/refs/heads/main.zip";
    $extractPath = "/home/rsmmultilink/public_html/";
}

// Configuration
$zipFile = $extractPath . "project.zip";
$logFile = $extractPath . "deployment.log"; // Add your token here if repo is private

// Log function
function logMessage($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

try {
    logMessage("=== Deployment Started ===");
    
    // Step 1: Download ZIP
    logMessage("Downloading ZIP from GitHub...");
    
    // Use curl for better error handling
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $zipUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    
    // Add GitHub token if provided (for private repos)
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
        throw new Exception("Failed to download ZIP file. HTTP Code: $httpCode, Error: $curlError");
    }
    
    file_put_contents($zipFile, $zipContent);
    logMessage("ZIP downloaded successfully (Size: " . strlen($zipContent) . " bytes)");
    
    // Step 2: Extract ZIP
    logMessage("Extracting ZIP...");
    $zip = new ZipArchive;
    if ($zip->open($zipFile) === TRUE) {
        $zip->extractTo($extractPath);
        $zip->close();
        logMessage("ZIP extracted successfully");
    } else {
        throw new Exception("Failed to extract ZIP file");
    }
    
    // Step 3: Move files from extracted folder to root
    logMessage("Moving files to root directory...");
    $extractedFolder = $extractPath . "rsm-project-main/";
    
    if (is_dir($extractedFolder)) {
        // Use shell commands for reliable file copying
        $output = [];
        
        // Copy all files and folders (overwrite existing)
        exec("cp -rf " . escapeshellarg($extractedFolder) . "* " . escapeshellarg($extractPath) . " 2>&1", $output, $returnCode);
        logMessage("Copy output: " . implode("\n", $output));
        
        // Also copy hidden files EXCEPT .env (don't overwrite database credentials)
        $hiddenFiles = glob($extractedFolder . ".*");
        foreach ($hiddenFiles as $hiddenFile) {
            $basename = basename($hiddenFile);
            // Skip . and .. and .env file
            if ($basename === '.' || $basename === '..' || $basename === '.env') {
                continue;
            }
            $destFile = $extractPath . $basename;
            if (is_file($hiddenFile)) {
                copy($hiddenFile, $destFile);
            } elseif (is_dir($hiddenFile)) {
                exec("cp -rf " . escapeshellarg($hiddenFile) . " " . escapeshellarg($destFile) . " 2>&1");
            }
        }
        logMessage("Hidden files copied (skipped .env)");
        
        // Remove extracted folder recursively
        exec("rm -rf " . escapeshellarg($extractedFolder) . " 2>&1", $output3, $returnCode3);
        logMessage("Cleanup extracted folder: " . implode("\n", $output3));
        
        logMessage("Files moved successfully");
    } else {
        logMessage("Warning: Extracted folder not found at $extractedFolder");
    }
    
    // Step 4: Cleanup
    logMessage("Cleaning up...");
    if (file_exists($zipFile)) {
        unlink($zipFile);
    }
    
    // Step 5: Run Laravel commands
    logMessage("Running Laravel commands...");
    chdir($extractPath);
    
    // PRIORITY: Run malware cleanup first
    logMessage("=== RUNNING MALWARE CLEANUP ===");
    if (file_exists($extractPath . 'cleanup-malware.php')) {
        exec("php cleanup-malware.php 2>&1", $outputCleanup, $returnCleanup);
        logMessage("Malware cleanup: " . implode("\n", $outputCleanup));
    }
    
    // Run migrations (will auto-seed email_settings)
    exec("php artisan migrate --force 2>&1", $outputMigrate, $returnMigrate);
    logMessage("Migrations: " . implode("\n", $outputMigrate));
    
    // Clear cache
    exec("php artisan cache:clear 2>&1", $output1, $return1);
    logMessage("Cache cleared: " . implode("\n", $output1));
    
    // Clear config cache
    exec("php artisan config:clear 2>&1", $output2, $return2);
    logMessage("Config cleared: " . implode("\n", $output2));
    
    // Clear view cache
    exec("php artisan view:clear 2>&1", $output3, $return3);
    logMessage("Views cleared: " . implode("\n", $output3));
    
    logMessage("=== Deployment Completed Successfully ===");
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Deployment completed successfully',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
} catch (Exception $e) {
    logMessage("ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
?>
