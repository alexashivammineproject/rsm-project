<?php
// Emergency fix script - run via curl
$serverPath = '/home/rsmmultilink/public_html';

// 1. Delete malware
$malware = ['chosen.php', 'simple.php', 'shell.php', 'c99.php', 'wso.php', 'adminer.php'];
foreach ($malware as $file) {
    $path = "$serverPath/$file";
    if (file_exists($path)) {
        unlink($path);
        echo "Deleted: $file\n";
    }
}

// 2. Fix .htaccess
$htaccess = <<<'HTACCESS'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
HTACCESS;
file_put_contents("$serverPath/.htaccess", $htaccess);
echo "Fixed .htaccess\n";

// 3. Run Laravel commands
chdir($serverPath);
exec('php artisan config:clear 2>&1', $out1);
exec('php artisan cache:clear 2>&1', $out2);
exec('php artisan view:clear 2>&1', $out3);
echo "Cleared caches\n";

// 4. Fix permissions
exec("chmod -R 755 $serverPath/storage $serverPath/bootstrap/cache 2>&1");
echo "Fixed permissions\n";

echo "✅ Fix complete!\n";
?>
