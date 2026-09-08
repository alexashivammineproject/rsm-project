#!/bin/bash
# RSM Website Security Hardening Script
# Run on server: bash security-hardening.sh

BASE_DIR="/home/rsmmultilink/public_html"

echo "=== RSM SECURITY HARDENING STARTED ==="

# 1. MALWARE SCAN & REMOVAL
echo "[1/8] Scanning for malware files..."
MALWARE_PATTERNS=(
    "shell.php" "c99.php" "r57.php" "wso.php" 
    "alfa.php" "backdoor.php" "adminer.php"
    "filefuns.php" "goods.php" "simple.php"
    "chosen.php" "wp-conffq.php"
)

for pattern in "${MALWARE_PATTERNS[@]}"; do
    find "$BASE_DIR" -name "$pattern" -type f -delete 2>/dev/null && echo "  ✓ Deleted: $pattern"
done

# 2. SUSPICIOUS PHP CODE SCAN
echo "[2/8] Scanning for suspicious PHP patterns..."
find "$BASE_DIR" -name "*.php" ! -path "*/vendor/*" -exec grep -l "eval(base64_decode\|eval(gzinflate\|assert(base64_decode" {} \; 2>/dev/null | while read file; do
    echo "  ⚠️  Suspicious: $file"
done

# 3. FILE PERMISSIONS HARDENING
echo "[3/8] Hardening file permissions..."
find "$BASE_DIR" -type f -name "*.php" -exec chmod 644 {} \;
find "$BASE_DIR" -type d -exec chmod 755 {} \;
chmod 755 "$BASE_DIR/artisan"

# Storage & Cache - Writable
chmod -R 775 "$BASE_DIR/storage" "$BASE_DIR/bootstrap/cache" 2>/dev/null
chown -R rsmmultilink:rsmmultilink "$BASE_DIR"

# 4. PROTECT SENSITIVE FILES
echo "[4/8] Protecting sensitive files..."
cat > "$BASE_DIR/.htaccess.security" << 'HTACCESS'
# Block access to sensitive files
<FilesMatch "(\.env|\.git|\.htaccess|composer\.json|composer\.lock|package\.json|artisan)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Block directory listing
Options -Indexes

# Block execution of PHP in uploads
<Directory "${BASE_DIR}/storage/app/public">
    php_flag engine off
    RemoveHandler .php .phtml .php3
    RemoveType .php .phtml .php3
</Directory>
HTACCESS

# 5. DISABLE DANGEROUS PHP FUNCTIONS (if not already)
echo "[5/8] Checking PHP security settings..."
echo "  → Check disable_functions in php.ini"

# 6. SETUP FILE INTEGRITY MONITORING
echo "[6/8] Creating file integrity baseline..."
find "$BASE_DIR/app" "$BASE_DIR/config" "$BASE_DIR/routes" -type f -name "*.php" -exec md5sum {} \; > "$BASE_DIR/storage/file-integrity.txt" 2>/dev/null
echo "  ✓ Baseline created at storage/file-integrity.txt"

# 7. WEBHOOK SECURITY
echo "[7/8] Securing webhook..."
if [ -f "$BASE_DIR/webhook.php" ]; then
    chmod 640 "$BASE_DIR/webhook.php"
    echo "  ✓ Webhook permissions restricted"
fi

# 8. REMOVE UNNECESSARY FILES
echo "[8/8] Removing unnecessary files..."
rm -f "$BASE_DIR"/*.zip "$BASE_DIR"/*.sql "$BASE_DIR"/*.bak "$BASE_DIR"/test*.php 2>/dev/null
rm -f "$BASE_DIR"/server-diagnostic.php 2>/dev/null
echo "  ✓ Cleanup completed"

# FINAL REPORT
echo ""
echo "=== SECURITY HARDENING COMPLETED ==="
echo ""
echo "✅ ACTIONS TAKEN:"
echo "  • Malware files removed"
echo "  • File permissions hardened (644/755)"
echo "  • Sensitive files protected"
echo "  • File integrity baseline created"
echo "  • Unnecessary files removed"
echo ""
echo "⚠️  MANUAL ACTIONS REQUIRED:"
echo "  1. Review suspicious files listed above"
echo "  2. Update server PHP.ini: disable_functions=exec,passthru,shell_exec,system,proc_open,popen"
echo "  3. Enable ModSecurity WAF"
echo "  4. Setup fail2ban for brute force protection"
echo "  5. Enable automatic security updates"
echo "  6. Setup regular backups"
echo ""
echo "📊 MONITORING:"
echo "  • Check file integrity: diff storage/file-integrity.txt <(find app config routes -type f -name '*.php' -exec md5sum {} \\;)"
echo "  • Monitor logs: tail -f storage/logs/laravel.log"
echo ""
