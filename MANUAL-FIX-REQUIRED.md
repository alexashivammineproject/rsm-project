# 🚨 MANUAL FIX REQUIRED - cPanel से करना होगा

## समस्या (Problem)

Webhook files copy नहीं कर पा रहा है। सर्वर पर manually fix करना होगा।

---

## ✅ SOLUTION - cPanel Terminal से ये Commands चलाओ

### Step 1: cPanel Login
```
Login: https://rsmmultilink.com:2083
Username: Your cPanel username
Password: Your cPanel password
```

### Step 2: Terminal Open करो
```
cPanel → Advanced → Terminal
```

### Step 3: ये Commands एक-एक करके चलाओ

#### 1. Public_html फोल्डर में जाओ
```bash
cd /home/rsmmultilink/public_html
```

#### 2. ZIP Download करो GitHub से
```bash
wget https://github.com/rsmmultilinkupdate-crypto/rsm-project/archive/refs/heads/main.zip -O latest.zip
```

#### 3. ZIP Extract करो
```bash
unzip -o latest.zip
```

#### 4. Files Copy करो Root में
```bash
cp -rf rsm-project-main/* ./
cp -rf rsm-project-main/.htaccess ./
cp -rf rsm-project-main/.gitignore ./
```

**IMPORTANT: .env file ko COPY MAT KARO (database credentials overwrite ho jayenge)**

#### 5. Cleanup करो
```bash
rm -rf rsm-project-main/
rm latest.zip
```

#### 6. Malware Delete करो
```bash
rm -f filefuns.php goods.php shell.php c99.php r57.php wso.php
```

#### 7. Permissions Fix करो
```bash
chmod 644 .htaccess
chmod 644 .env
chmod 755 public
chmod 755 storage
chmod 755 bootstrap/cache
```

#### 8. Laravel Cache Clear करो
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

#### 9. Database Check करो
```bash
php artisan migrate --force
```

---

## ✅ Verify करो

### 1. Ping Test
```bash
curl https://rsmmultilink.com/ping.php
```
Expected: `PONG - 2026-06-11 ...`

### 2. Check Script
```bash
curl https://rsmmultilink.com/check.php
```
Expected: System check details

### 3. Website
```bash
curl https://www.rsmmultilink.com
```
Expected: HTML content (not blank)

---

## 🔍 अगर अभी भी Blank Page आए

### Error Log देखो
```bash
tail -50 /home/rsmmultilink/public_html/storage/logs/laravel.log
```

### PHP Error Log
```bash
tail -50 /home/rsmmultilink/public_html/error_log
```

### Database Test करो
```bash
php artisan tinker
# फिर type करो:
DB::connection()->getPdo();
exit
```

---

## 🔐 Security (Website चलने के बाद)

### 1. Diagnostic Files Delete करो
```bash
cd /home/rsmmultilink/public_html
rm -f ping.php check.php test-db.php status.php emergency-fix.php deploy-fix.php
rm -f URGENT-FIX-DATABASE.txt FIX-STEPS-*.md MANUAL-FIX-REQUIRED.md
```

### 2. Passwords बदलो
- cPanel password
- Database password (if needed)
- FTP password

### 3. Malware Scan करो
```bash
grep -r "eval(" --include="*.php" .
grep -r "base64_decode" --include="*.php" .
```

---

## 📞 Still Not Working?

### Screenshots लो:
1. Terminal commands की output
2. Error logs की last 50 lines
3. Website pe jo दिख रहा है (blank page)

### Mujhe bhejo:
1. Terminal output screenshots
2. `/home/rsmmultilink/public_html/storage/logs/laravel.log` की last 50 lines
3. `/home/rsmmultilink/public_html/error_log` की last 50 lines

---

## 🎯 Quick Command Sequence (सब एक साथ)

```bash
cd /home/rsmmultilink/public_html && \
wget https://github.com/rsmmultilinkupdate-crypto/rsm-project/archive/refs/heads/main.zip -O latest.zip && \
unzip -o latest.zip && \
cp -rf rsm-project-main/* ./ && \
cp -f rsm-project-main/.htaccess ./ && \
cp -f rsm-project-main/.gitignore ./ && \
rm -rf rsm-project-main/ latest.zip && \
rm -f filefuns.php goods.php shell.php c99.php && \
chmod 644 .htaccess .env && \
chmod -R 755 storage bootstrap/cache && \
php artisan config:clear && \
php artisan cache:clear && \
php artisan view:clear && \
echo "DEPLOYMENT COMPLETED - Check website now"
```

---

**Last Updated:** 2026-06-11 06:20 UTC
**Issue:** Webhook not copying files properly
**Solution:** Manual deployment via cPanel Terminal
