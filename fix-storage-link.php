<?php
/**
 * Fix Storage Symlink
 * 
 * Run this file via browser: https://rsmmultilink.com/fix-storage-link.php
 * 
 * This creates a symlink from public/storage to storage/app/public
 */

echo "<h2>Storage Symlink Fix</h2>";
echo "<pre>";

$targetPath = __DIR__ . '/storage/app/public';
$linkPath = __DIR__ . '/public/storage';

echo "Target: $targetPath\n";
echo "Link: $linkPath\n\n";

// Check if target exists
if (!is_dir($targetPath)) {
    echo "❌ ERROR: Target directory does not exist: $targetPath\n";
    exit;
}

// Remove existing link/directory if exists
if (file_exists($linkPath)) {
    if (is_link($linkPath)) {
        unlink($linkPath);
        echo "✓ Removed existing symlink\n";
    } elseif (is_dir($linkPath)) {
        echo "⚠ WARNING: public/storage exists as a directory (not a symlink)\n";
        echo "Please manually delete it or rename it, then run this script again.\n";
        exit;
    } else {
        unlink($linkPath);
        echo "✓ Removed existing file\n";
    }
}

// Create symlink
if (symlink($targetPath, $linkPath)) {
    echo "✓ Symlink created successfully!\n\n";
    
    // Verify
    if (is_link($linkPath)) {
        echo "✓ Verification: Symlink is working\n";
        echo "✓ Link points to: " . readlink($linkPath) . "\n\n";
        
        // Test with a sample file
        $testFiles = glob($targetPath . '/images/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        if (!empty($testFiles)) {
            $testFile = basename($testFiles[0]);
            $publicUrl = '/storage/images/' . $testFile;
            echo "✓ Test URL: <a href='$publicUrl' target='_blank'>$publicUrl</a>\n\n";
        }
        
        echo "========================================\n";
        echo "✓ STORAGE SYMLINK FIXED SUCCESSFULLY!\n";
        echo "========================================\n\n";
        echo "Now your blog images will display correctly!\n";
        echo "Test your blog page: <a href='/blog/' target='_blank'>/blog/</a>\n\n";
        echo "IMPORTANT: Delete this file after use for security!\n";
    } else {
        echo "❌ ERROR: Symlink created but verification failed\n";
    }
} else {
    echo "❌ ERROR: Failed to create symlink\n";
    echo "Possible reasons:\n";
    echo "1. Insufficient permissions\n";
    echo "2. Symlinks disabled on server\n";
    echo "3. Safe mode restrictions\n\n";
    echo "Alternative: Run via SSH:\n";
    echo "cd /home/rsmmultilink/public_html\n";
    echo "php artisan storage:link\n";
}

echo "</pre>";
?>
