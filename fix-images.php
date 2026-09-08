<?php
/**
 * Image Fix Script - Run on live server
 * URL: https://rsmmultilink.com/fix-images.php?key=rsm123fix
 * DELETE THIS FILE AFTER USE!
 */

if (!isset($_GET['key']) || $_GET['key'] !== 'rsm123fix') {
    die("Access denied");
}

$action = $_GET['action'] ?? 'check';

// DB connection
$host = '127.0.0.1';
$user = 'rsmmultilink';
$pass = 'rsmupdate@@';
$db   = 'rsmmultilink_rsmupdate';
$storageRoot = '/home/rsmmultilink/public_html/storage/app/public/';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("DB Error: " . $conn->connect_error);
}

echo "<pre style='font-family:monospace;font-size:13px'>";
echo "=== IMAGE FIX SCRIPT ===\n\n";

if ($action === 'check') {
    // ---- STEP 1: Check what paths are in DB ----
    echo "--- BLOG IMAGE PATHS (first 10) ---\n";
    $r = $conn->query("SELECT id, image FROM blogs LIMIT 10");
    while ($row = $r->fetch_assoc()) {
        $path = $storageRoot . $row['image'];
        $exists = file_exists($path) ? '✅ EXISTS' : '❌ MISSING';
        echo "ID {$row['id']}: {$row['image']} → $exists\n";
    }

    echo "\n--- PRODUCT IMAGE PATHS (first 10) ---\n";
    $r = $conn->query("SELECT id, image FROM products LIMIT 10");
    while ($row = $r->fetch_assoc()) {
        $path = $storageRoot . $row['image'];
        $exists = file_exists($path) ? '✅ EXISTS' : '❌ MISSING';
        echo "ID {$row['id']}: {$row['image']} → $exists\n";
    }

    echo "\n--- MULTI IMAGES (first 10) ---\n";
    $r = $conn->query("SELECT id, image FROM multi_images LIMIT 10");
    while ($row = $r->fetch_assoc()) {
        $path = $storageRoot . $row['image'];
        $exists = file_exists($path) ? '✅ EXISTS' : '❌ MISSING';
        echo "ID {$row['id']}: {$row['image']} → $exists\n";
    }

    echo "\n--- PCATEGORIES IMAGE PATHS (first 10) ---\n";
    $r = $conn->query("SELECT id, image FROM pcategories LIMIT 10");
    while ($row = $r->fetch_assoc()) {
        $path = $storageRoot . $row['image'];
        $exists = file_exists($path) ? '✅ EXISTS' : '❌ MISSING';
        echo "ID {$row['id']}: {$row['image']} → $exists\n";
    }

    echo "\n--- FILES IN storage/app/public/ ROOT (first 20) ---\n";
    $files = array_slice(scandir($storageRoot), 2, 20);
    foreach ($files as $f) {
        echo "$f\n";
    }

    echo "\n--- FILES IN storage/app/public/images/ (first 20) ---\n";
    if (is_dir($storageRoot . 'images/')) {
        $files = array_slice(scandir($storageRoot . 'images/'), 2, 20);
        foreach ($files as $f) {
            echo "images/$f\n";
        }
        $total = count(scandir($storageRoot . 'images/')) - 2;
        echo "... TOTAL in images/: $total files\n";
    } else {
        echo "❌ images/ folder does not exist!\n";
    }

    echo "\n\nTO FIX: Run ?key=rsm123fix&action=fix\n";

} elseif ($action === 'fix') {
    // ---- STEP 2: Create images/ folder if needed and fix paths ----
    echo "--- STARTING FIX ---\n\n";

    // 1. Create images/ subfolder if not exists
    $imagesDir = $storageRoot . 'images/';
    if (!is_dir($imagesDir)) {
        mkdir($imagesDir, 0755, true);
        echo "✅ Created images/ folder\n";
    } else {
        echo "✅ images/ folder exists\n";
    }

    // 2. Move any images that are in root but should be in images/
    // (files that are NOT in images/ subdir but exist in root)
    $moved = 0;
    $rootFiles = scandir($storageRoot);
    foreach ($rootFiles as $file) {
        if ($file === '.' || $file === '..') continue;
        $fullPath = $storageRoot . $file;
        // Only move image files, not folders
        if (is_file($fullPath)) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
                // Check if this file is referenced in DB as images/filename
                $r = $conn->query("SELECT COUNT(*) as cnt FROM blogs WHERE image = 'images/$file'");
                $row = $r->fetch_assoc();
                $inBlogAsImages = $row['cnt'] > 0;

                $r2 = $conn->query("SELECT COUNT(*) as cnt FROM products WHERE image = 'images/$file'");
                $row2 = $r2->fetch_assoc();
                $inProductAsImages = $row2['cnt'] > 0;

                // Also copy to images/ so both paths work
                if (!file_exists($imagesDir . $file)) {
                    copy($fullPath, $imagesDir . $file);
                    $moved++;
                }
            }
        }
    }
    echo "✅ Copied $moved files to images/ folder\n";

    // 3. Also copy from images/ to root so both paths work
    $copiedToRoot = 0;
    if (is_dir($imagesDir)) {
        $imgFiles = scandir($imagesDir);
        foreach ($imgFiles as $file) {
            if ($file === '.' || $file === '..') continue;
            $src = $imagesDir . $file;
            $dst = $storageRoot . $file;
            if (is_file($src) && !file_exists($dst)) {
                copy($src, $dst);
                $copiedToRoot++;
            }
        }
    }
    echo "✅ Copied $copiedToRoot files from images/ to root\n";

    // 4. Set permissions
    exec("chmod -R 644 " . escapeshellarg($imagesDir) . "*.jpg 2>/dev/null");
    exec("chmod -R 644 " . escapeshellarg($imagesDir) . "*.jpeg 2>/dev/null");
    exec("chmod -R 644 " . escapeshellarg($imagesDir) . "*.png 2>/dev/null");
    exec("chmod -R 644 " . escapeshellarg($imagesDir) . "*.webp 2>/dev/null");
    exec("chown -R rsmmultilink:rsmmultilink " . escapeshellarg($storageRoot));
    echo "✅ Permissions fixed\n";

    // 5. Count total
    $totalRoot   = count(array_filter(scandir($storageRoot), fn($f) => is_file($storageRoot.$f)));
    $totalImages = is_dir($imagesDir) ? count(scandir($imagesDir)) - 2 : 0;
    echo "\n📊 Total files in root: $totalRoot\n";
    echo "📊 Total files in images/: $totalImages\n";

    // 6. Test a few DB paths
    echo "\n--- TESTING DB PATHS AFTER FIX ---\n";
    $r = $conn->query("SELECT id, image FROM blogs LIMIT 5");
    while ($row = $r->fetch_assoc()) {
        $path = $storageRoot . $row['image'];
        $exists = file_exists($path) ? '✅' : '❌';
        echo "$exists {$row['image']}\n";
    }
    $r = $conn->query("SELECT id, image FROM products LIMIT 5");
    while ($row = $r->fetch_assoc()) {
        $path = $storageRoot . $row['image'];
        $exists = file_exists($path) ? '✅' : '❌';
        echo "$exists {$row['image']}\n";
    }

    echo "\n✅ FIX COMPLETE! Check website now.\n";
    echo "⚠️  DELETE this file after use!\n";
}

echo "</pre>";
$conn->close();
?>
