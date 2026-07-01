# 🔧 RSM Website Troubleshooting Guide
**Last Updated:** July 1, 2026  
**Project:** RSM Multilink Laravel Application

---

## 📋 QUICK REFERENCE

### Website URLs
- **Live:** https://rsmmultilink.com
- **Local:** http://localhost/rsmupdatenew/public
- **GitHub:** https://github.com/rsmmultilinkupdate-crypto/rsm-project (Private)

### Server Details
- **Server IP:** 37.46.127.18
- **Path:** `/home/rsmmultilink/public_html`
- **PHP:** 8.2.30
- **Laravel:** 10.50.2
- **Database:** rsmmultilink_rsmupdate

---

## 🚨 COMMON ISSUES & FIXES

### Issue #1: WHITE SCREEN / 500 ERROR

**Root Cause:** Database connection failed or cache corruption

**Quick Fix:**
```bash
cd /home/rsmmultilink/public_html
php artisan config:clear
php artisan cache:clear
php artisan view:clear
chmod -R 777 storage bootstrap/cache
```

**Check Logs:**
```bash
tail -50 /home/rsmmultilink/public_html/storage/logs/laravel.log
tail -50 /var/log/apache2/error_log
```

---

### Issue #2: IMAGES NOT SHOWING

**Root Cause:** Storage symlink broken or files in wrong location

**Quick Fix:**
```bash
cd /home/rsmmultilink/public_html
php artisan storage:link
cd storage/app/public
find images -type f \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" -o -name "*.webp" \) -exec cp -n {} . \;
chmod 755 *.jpg *.jpeg *.png *.webp 2>/dev/null
```

**Verify:**
```bash
curl -I https://rsmmultilink.com/storage/images/test.jpg
```

---

### Issue #3: MALWARE / VIRUS DETECTED

**Root Cause:** File upload vulnerability exploited

**Malware Files to Delete:**
- shell.php, c99.php, r57.php, wso.php
- alfa.php, backdoor.php, adminer.php
- filefuns.php, goods.php, simple.php
- chosen.php, wp-conffq.php

**Quick Scan & Clean:**
```bash
cd /home/rsmmultilink/public_html
bash security-hardening.sh
```

**Manual Cleanup:**
```bash
find /home/rsmmultilink/public_html -name "shell.php" -delete
find /home/rsmmultilink/public_html -name "c99.php" -delete
grep -rl "eval(base64_decode" /home/rsmmultilink/public_html --include="*.php" --exclude-dir=vendor
```

---

### Issue #4: DEPLOYMENT FAILED

**Root Cause:** Git conflicts or webhook error

**Quick Fix:**
```bash
cd /home/rsmmultilink/public_html
curl "https://rsmmultilink.com/webhook.php?key=rsm123"
sleep 5
php artisan config:clear
php artisan cache:clear
```

**Check Deployment Log:**
```bash
tail -50 /home/rsmmultilink/public_html/deployment.log
```

---

### Issue #5: DATABASE CONNECTION ERROR

**Root Cause:** Wrong credentials or database not exists

**Check .env File:**
```bash
cat /home/rsmmultilink/public_html/.env | grep DB_
```

**Test Connection:**
```bash
mysql -u rsmmultilink_rsmupdate -p'rsmupdate@@' rsmmultilink_rsmupdate -e "SHOW TABLES;"
```

**Fix:**
```bash
cd /home/rsmmultilink/public_html
php artisan migrate --force
```

---

### Issue #6: .HTACCESS ERROR (500)

**Root Cause:** Invalid directives in .htaccess

**Common Errors:**
- `<Directory>` not allowed - Remove Directory blocks
- `php_flag` invalid - Remove or move to .user.ini

**Quick Fix:**
```bash
cd /home/rsmmultilink/public_html
# Backup
cp .htaccess .htaccess.backup

# Remove problematic lines
sed -i '/<Directory/,/<\/Directory>/d' .htaccess

# Restart Apache
systemctl restart httpd
```

---

### Issue #7: PERMISSION DENIED ERRORS

**Root Cause:** Wrong file ownership or permissions

**Quick Fix:**
```bash
cd /home/rsmmultilink/public_html
chown -R rsmmultilink:rsmmultilink .
find . -type f -name "*.php" -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 775 storage bootstrap/cache
```

---

## 🔒 SECURITY CHECKLIST

### After Malware Attack
- [ ] Run `bash security-hardening.sh`
- [ ] Check file integrity: `diff storage/file-integrity.txt <(find app config routes -type f -name '*.php' -exec md5sum {} \;)`
- [ ] Review suspicious files in logs
- [ ] Change all passwords (.env, cPanel, database)
- [ ] Update all packages: `composer update`
- [ ] Check cron jobs: `crontab -l`

### Monthly Security Tasks
- [ ] Scan for malware files
- [ ] Check file permissions
- [ ] Review Laravel logs
- [ ] Update dependencies
- [ ] Backup database
- [ ] Test deployment webhook

---

## 📁 FILE STRUCTURE

```
/home/rsmmultilink/public_html/
├── app/                    # Laravel application code
├── public/                 # Web root (index.php)
│   └── storage/           # Symlink to storage/app/public
├── storage/
│   ├── app/public/        # Uploaded files (images, pdfs)
│   ├── logs/              # Laravel logs
│   └── framework/         # Cache, sessions, views
├── vendor/                # Composer dependencies (never edit)
├── .env                   # Environment config (SECRET!)
├── .htaccess             # Apache rules
├── webhook.php           # Auto-deployment script
└── artisan               # Laravel CLI tool
```

---

## 🔄 DEPLOYMENT WORKFLOW

### Local → Live Deployment

1. **Make changes locally** at `/opt/lampp/htdocs/rsmupdatenew`

2. **Commit to GitHub:**
```bash
cd /opt/lampp/htdocs/rsmupdatenew
git add .
git commit -m "Your change description"
git push origin main
```

3. **Deploy to server:**
```bash
curl "https://rsmmultilink.com/webhook.php?key=rsm123"
```

4. **Verify:**
```bash
curl -I https://rsmmultilink.com
```

---

## 🛠️ USEFUL COMMANDS

### Laravel Artisan
```bash
# Clear all caches
php artisan optimize:clear

# View routes
php artisan route:list

# Database migration
php artisan migrate --force

# Create storage symlink
php artisan storage:link

# Check Laravel version
php artisan --version
```

### Server Management
```bash
# Restart Apache
systemctl restart httpd

# Check Apache status
systemctl status httpd

# View Apache error log
tail -f /var/log/apache2/error_log

# View Laravel log
tail -f /home/rsmmultilink/public_html/storage/logs/laravel.log

# Check disk space
df -h

# Check PHP version
php -v
```

### Database
```bash
# Connect to database
mysql -u rsmmultilink_rsmupdate -p'rsmupdate@@' rsmmultilink_rsmupdate

# Export database
mysqldump -u rsmmultilink_rsmupdate -p'rsmupdate@@' rsmmultilink_rsmupdate > backup.sql

# Import database
mysql -u rsmmultilink_rsmupdate -p'rsmupdate@@' rsmmultilink_rsmupdate < backup.sql
```

---

## 🎯 EMERGENCY RECOVERY

### Complete Site Down
1. Check Apache: `systemctl status httpd`
2. Check Laravel logs: `tail -50 storage/logs/laravel.log`
3. Clear all caches: `php artisan optimize:clear`
4. Fix permissions: `chmod -R 775 storage bootstrap/cache`
5. Restart Apache: `systemctl restart httpd`

### Database Corrupted
1. Restore from backup
2. Run migrations: `php artisan migrate --force`
3. Clear config: `php artisan config:clear`

### Files Deleted/Missing
1. Redeploy from GitHub: `curl "https://rsmmultilink.com/webhook.php?key=rsm123"`
2. Restore .env from backup
3. Run composer install if vendor missing

---

## 📞 CRITICAL CREDENTIALS

**Server SSH:**
- Host: 37.46.127.18
- User: root
- Password: lWkqa2DzMjYY@%@@

**Database:**
- Host: localhost
- Database: rsmmultilink_rsmupdate
- User: rsmmultilink_rsmupdate
- Password: rsmupdate@@

**Webhook:**
- URL: https://rsmmultilink.com/webhook.php?key=rsm123
- Security Key: rsm123

**Email:**
- Host: mail.rsmmultilink.com
- Port: 587
- User: query@rsmmultilink.com
- Password: v8{hh6zMJsDU

---

## 📊 MONITORING

### Health Check Commands
```bash
# Website uptime
curl -I https://rsmmultilink.com

# Check images
curl -I https://rsmmultilink.com/storage/images/test.jpg

# Check blog page
curl -I https://rsmmultilink.com/blog

# Check product page
curl -I https://rsmmultilink.com/product/catproducts/men-health-73

# Database connection
php artisan tinker --execute="DB::connection()->getPdo();"
```

### Log Monitoring
```bash
# Watch Laravel log
tail -f /home/rsmmultilink/public_html/storage/logs/laravel.log

# Watch Apache error log
tail -f /var/log/apache2/error_log

# Count errors in last hour
grep "ERROR" storage/logs/laravel.log | tail -100
```

---

## 🔐 SECURITY BEST PRACTICES

1. **Never commit .env to Git**
2. **Keep Laravel & PHP updated**
3. **Use 644 for files, 755 for directories**
4. **Disable PHP in upload folders**
5. **Regular malware scans**
6. **Strong passwords everywhere**
7. **Enable HTTPS always**
8. **Regular backups**
9. **Monitor logs daily**
10. **Limit file upload types**

---

## 🆘 WHEN ALL ELSE FAILS

### Nuclear Option - Full Reset
```bash
# 1. Backup database
mysqldump -u rsmmultilink_rsmupdate -p'rsmupdate@@' rsmmultilink_rsmupdate > emergency_backup.sql

# 2. Backup .env
cp /home/rsmmultilink/public_html/.env /tmp/env_backup

# 3. Delete everything
cd /home/rsmmultilink/public_html
rm -rf * .??*

# 4. Fresh deploy from GitHub
curl -L "https://github.com/rsmmultilinkupdate-crypto/rsm-project/archive/refs/heads/main.zip" -o project.zip
unzip project.zip
mv rsm-project-main/* .
rm -rf rsm-project-main project.zip

# 5. Restore .env
cp /tmp/env_backup .env

# 6. Install dependencies
composer install --no-dev

# 7. Fix permissions
chown -R rsmmultilink:rsmmultilink .
chmod -R 775 storage bootstrap/cache

# 8. Run migrations
php artisan migrate --force

# 9. Link storage
php artisan storage:link

# 10. Clear caches
php artisan optimize:clear

# 11. Restart Apache
systemctl restart httpd
```

---

## 📝 CHANGE LOG

### July 1, 2026 - Major Security Update
- Removed 12 malware files
- Fixed white screen error (database connection)
- Fixed all missing images (storage symlink + file copy)
- Added security hardening (.htaccess rules)
- Secured file permissions
- Created file integrity baseline
- Deployed to production successfully

**Issues Fixed:**
1. Database connection error → Fixed .env and cleared cache
2. Images not loading → Storage symlink + copied files from images folder
3. Malware infection → Removed shell.php, c99.php, backdoor.php, etc.
4. 500 error from .htaccess → Removed invalid <Directory> directive
5. Permission issues → Reset all to 644/755/775

---

## 🎓 HELPFUL RESOURCES

- Laravel Docs: https://laravel.com/docs/10.x
- cPanel Docs: https://docs.cpanel.net/
- PHP Manual: https://www.php.net/manual/en/
- Apache Docs: https://httpd.apache.org/docs/

---

**END OF TROUBLESHOOTING GUIDE**
