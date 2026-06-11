# 🚨 WEBSITE DOWN - MANUAL FIX REQUIRED

## PROBLEM SUMMARY
Website showing **WHITE BLANK PAGE** due to:
1. ❌ **DATABASE CONNECTION FAILED** - Wrong password in .env file
2. ❌ **MALWARE INFECTION** - Files `filefuns.php` and `goods.php` attacking .htaccess

---

## ✅ FIXES ALREADY APPLIED (via Git Push)

I have pushed these fixes to GitHub:
- ✅ Fixed PHP 8.2 compatibility in server.php
- ✅ Added malware cleanup script (cleanup-malware.php)
- ✅ Updated webhook to auto-run malware cleanup
- ✅ Added .gitignore rules to block malware from git
- ✅ Created diagnostic tools (status.php, test-db.php)

**Webhook deployment completed successfully** - Code is updated on server.

---

## 🔧 MANUAL STEPS YOU MUST DO NOW

### STEP 1: FIX DATABASE PASSWORD (CRITICAL - 5 minutes)

1. **Login to cPanel** at https://rsmmultilink.com:2083

2. **Find MySQL Database Section**
   - Click "MySQL Databases"
   - Look for database: `rsmmultilink_rsmupdate`
   - Look for user: `rsmmultilink_rsmupdate`

3. **Check Database Password**
   - If database doesn't exist: Create new database with name `rsmmultilink_rsmupdate`
   - If user doesn't exist: Create new user with username `rsmmultilink_rsmupdate` and password `rsmupdate@@`
   - Add user to database with ALL PRIVILEGES
   - **WRITE DOWN THE CORRECT PASSWORD**

4. **Update .env File on Server**
   - Go to cPanel File Manager
   - Navigate to: `/home/rsmmultilink/public_html/`
   - Edit file: `.env`
   - Find these lines:
     ```
     DB_DATABASE=rsmmultilink_rsmupdate
     DB_USERNAME=rsmmultilink_rsmupdate
     DB_PASSWORD=rsmupdate@@
     ```
   - Replace `rsmupdate@@` with the CORRECT password from cPanel
   - **SAVE THE FILE**

5. **Clear Laravel Cache** (via cPanel Terminal or SSH)
   ```bash
   cd /home/rsmmultilink/public_html
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

---

### STEP 2: DELETE MALWARE FILES (CRITICAL - 3 minutes)

**Option A: Use Cleanup Script (Recommended)**
```bash
cd /home/rsmmultilink/public_html
php cleanup-malware.php
```

**Option B: Manual Deletion via cPanel File Manager**
1. Login to cPanel File Manager
2. Navigate to `/home/rsmmultilink/public_html/`
3. Look for these files and **DELETE THEM**:
   - `filefuns.php` ⚠️ **CRITICAL MALWARE**
   - `goods.php` ⚠️ **CRITICAL MALWARE**
   - `qwe.php` (if exists)
   - `shell.php` (if exists)
   - Any other suspicious .php files in root directory

4. **Empty Trash** in File Manager

---

### STEP 3: VERIFY WEBSITE IS WORKING (2 minutes)

1. **Check Status Page**
   - Visit: https://www.rsmmultilink.com/status.php
   - Should show green checkmarks for all system checks
   - If red errors, follow the instructions shown on page

2. **Test Database Connection**
   - Visit: https://www.rsmmultilink.com/test-db.php
   - Should show "✅ DATABASE CONNECTION SUCCESSFUL!"
   - If failed, go back to STEP 1

3. **Visit Main Website**
   - Visit: https://www.rsmmultilink.com
   - Should load homepage properly
   - If still blank, check error logs

---

### STEP 4: CHECK ERROR LOGS (if website still not working)

**Via cPanel:**
1. Go to cPanel → Metrics → Errors
2. Check for recent errors

**Via File Manager:**
1. Open: `/home/rsmmultilink/public_html/storage/logs/laravel.log`
2. Look at bottom of file for latest errors
3. Send me the last 20 lines if you need help

---

### STEP 5: SECURITY CLEANUP (After Website is Working)

1. **Change All Passwords**
   - cPanel password
   - FTP password
   - Database password (if you haven't already)
   - SSH password

2. **Delete Diagnostic Files** (for security)
   ```bash
   cd /home/rsmmultilink/public_html
   rm status.php
   rm test-db.php
   rm URGENT-FIX-DATABASE.txt
   rm FIX-STEPS-MANUAL.md
   ```

3. **Check for Backdoors**
   - Scan all recently uploaded files/images
   - Check public/uploads/ folder for suspicious .php files
   - Use cPanel virus scanner if available

4. **Update .htaccess Protection** (optional but recommended)
   Add this to bottom of .htaccess:
   ```apache
   # Block malware patterns
   <FilesMatch "(filefuns|goods|shell|c99|r57|wso|alfa)\.php$">
       Order allow,deny
       Deny from all
   </FilesMatch>
   ```

---

## 📞 NEED HELP?

If website is still not working after following all steps:

1. **Take screenshots of:**
   - status.php page
   - test-db.php page
   - Any error messages

2. **Send me:**
   - The screenshots
   - Last 20 lines of laravel.log
   - Tell me which step you're stuck on

---

## ⚡ QUICK CHECKLIST

- [ ] Fixed database password in .env
- [ ] Cleared Laravel cache
- [ ] Deleted filefuns.php
- [ ] Deleted goods.php
- [ ] Verified status.php shows all green
- [ ] Verified test-db.php connects successfully
- [ ] Website homepage loads properly
- [ ] Changed all passwords
- [ ] Deleted diagnostic files

---

**Last Updated:** 2026-06-11 06:00 UTC
**Git Commit:** 7c1c033
**Deployment Status:** ✅ All code deployed via webhook
**Remaining:** Manual database fix + malware deletion
