# 🚨 WEBSITE CHALANE KA MANUAL TARIKA

Bhai, webhook kaam nahi kar raha properly. Tumhe manually cPanel se fix karna hoga.

---

## 📋 YE KYA KARNA HAI (5-10 Minutes)

1. cPanel login karo
2. Terminal kholo
3. Neeche diye commands copy-paste karo
4. Website chal jayegi ✅

---

## 🔧 STEP-BY-STEP GUIDE

### STEP 1: cPanel Login Karo

```
URL: https://rsmmultilink.com:2083
Username: Tumhara cPanel username
Password: Tumhara cPanel password
```

### STEP 2: Terminal Kholo

```
cPanel dashboard mein jao
→ "Advanced" section mein
→ "Terminal" pe click karo
→ Black screen khul jayegi
```

### STEP 3: YE COMMAND COPY KARO AUR PASTE KARO

**Ek baar mein saari commands:**

```bash
cd /home/rsmmultilink/public_html && wget https://github.com/rsmmultilinkupdate-crypto/rsm-project/archive/refs/heads/main.zip -O latest.zip && unzip -o latest.zip && cp -rf rsm-project-main/* ./ && cp -f rsm-project-main/.htaccess ./ && cp -f rsm-project-main/.gitignore ./ && rm -rf rsm-project-main/ latest.zip && rm -f filefuns.php goods.php shell.php c99.php && chmod 644 .htaccess .env && chmod -R 755 storage bootstrap/cache && php artisan config:clear && php artisan cache:clear && php artisan view:clear && echo "HO GAYA - Ab website check karo"
```

**Agar ek saath nahi chala to ek-ek karke:**

```bash
# 1. Folder mein jao
cd /home/rsmmultilink/public_html

# 2. Latest code download karo
wget https://github.com/rsmmultilinkupdate-crypto/rsm-project/archive/refs/heads/main.zip -O latest.zip

# 3. Extract karo
unzip -o latest.zip

# 4. Files copy karo
cp -rf rsm-project-main/* ./
cp -f rsm-project-main/.htaccess ./

# 5. Cleanup
rm -rf rsm-project-main/
rm latest.zip

# 6. Malware delete
rm -f filefuns.php goods.php shell.php

# 7. Permissions fix
chmod 644 .htaccess
chmod -R 755 storage

# 8. Cache clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 9. Done!
echo "HO GAYA!"
```

---

## ✅ CHECK KARO - Website Chali Ya Nahi

### Test 1: Ping
Terminal mein type karo:
```bash
curl https://rsmmultilink.com/ping.php
```
**Expected:** `PONG - 2026-06-11 ...` kuch aisa dikhna chahiye

### Test 2: Website
Browser mein kholo:
```
https://www.rsmmultilink.com
```
**Expected:** Homepage load hona chahiye (no blank page)

### Test 3: Admin
```
https://www.rsmmultilink.com/admin/login
```
**Expected:** Login page dikhna chahiye

---

## 🐛 Agar Abhi Bhi Problem Hai

### Error Log Dekho
```bash
tail -50 /home/rsmmultilink/public_html/storage/logs/laravel.log
```

Isko copy karke mujhe bhejo.

### Database Check
```bash
php artisan tinker
```
Phir type karo:
```php
DB::connection()->getPdo();
```
Agar error aaye to database password galat hai .env file mein.

---

## 🗑️ Cleanup (Website Chalne Ke Baad)

Ye extra files delete karo security ke liye:

```bash
cd /home/rsmmultilink/public_html
rm -f ping.php check.php test-db.php status.php
rm -f emergency-fix.php deploy-fix.php
rm -f URGENT-FIX-DATABASE.txt
rm -f FIX-STEPS-*.md MANUAL-FIX-REQUIRED.md HINDI-MANUAL-FIX.md
```

---

## 💡 IMPORTANT NOTES

### ⚠️ .env File
- Ye file KABHI replace mat karna manually
- Isme tumhare database credentials hain
- Agar galti se delete ho jaye:
  ```
  DB_DATABASE=rsmmultilink_rsmupdate
  DB_USERNAME=rsmmultilink_rsmupdate
  DB_PASSWORD=rsmupdate@@
  ```

### 🔒 Security
Website chalne ke baad:
1. **Malware check:** cPanel → Security → Virus Scanner
2. **Passwords:** cPanel, Database, FTP - sab badal do
3. **Backups:** cPanel → Backup → Generate Backup

---

## 📞 Help Chahiye?

Agar terminal commands se problem aa rahi hai:

**Option 1: File Manager Use Karo**
1. cPanel → File Manager
2. public_html mein jao
3. Upload button → GitHub se manual download karo ZIP
4. Extract karo
5. Files move karo manually

**Option 2: Mujhe Bhejo**
1. Terminal ki screenshot
2. Error log ki lines
3. Website ka screenshot (blank page)

---

**Bhai, bas ye commands cPanel Terminal mein paste kar do. 5 minute ka kaam hai! 🚀**

**Database credentials sahi hain tumhare:**
- Database: `rsmmultilink_rsmupdate`
- Username: `rsmmultilink_rsmupdate`  
- Password: `rsmupdate@@`

**Bas manual deployment karna hai terminal se! All the best! 💪**
