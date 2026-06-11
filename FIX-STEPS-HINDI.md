# 🚨 वेबसाइट डाउन है - तुरंत ठीक करें

## समस्या का सारांश
वेबसाइट पर **सफेद खाली पेज** दिख रहा है क्योंकि:
1. ❌ **डेटाबेस कनेक्शन फेल** - .env फाइल में गलत पासवर्ड
2. ❌ **MALWARE इन्फेक्शन** - `filefuns.php` और `goods.php` फाइलें .htaccess को अटैक कर रही हैं

---

## ✅ जो मैंने ठीक कर दिया (Git Push के जरिए)

मैंने ये फिक्सेस GitHub पर पुश कर दिए हैं:
- ✅ server.php में PHP 8.2 compatibility fix
- ✅ Malware cleanup script बनाया (cleanup-malware.php)
- ✅ Webhook को अपडेट किया auto malware cleanup के लिए
- ✅ .gitignore में malware rules add किए
- ✅ Diagnostic tools बनाए (status.php, test-db.php)

**Webhook deployment successfully complete** - सर्वर पर कोड अपडेट हो गया है।

---

## 🔧 आपको अभी ये करना होगा (MANUAL STEPS)

### स्टेप 1: डेटाबेस पासवर्ड ठीक करें (जरूरी - 5 मिनट)

1. **cPanel में लॉगिन करें** - https://rsmmultilink.com:2083

2. **MySQL Database सेक्शन खोलें**
   - "MySQL Databases" पर क्लिक करें
   - यह database ढूंढें: `rsmmultilink_rsmupdate`
   - यह user ढूंढें: `rsmmultilink_rsmupdate`

3. **Database Password चेक करें**
   - अगर database नहीं है: नया database बनाएं नाम के साथ `rsmmultilink_rsmupdate`
   - अगर user नहीं है: नया user बनाएं username `rsmmultilink_rsmupdate` और password `rsmupdate@@`
   - User को database से link करें with ALL PRIVILEGES
   - **सही पासवर्ड को कहीं लिख लें**

4. **Server पर .env File Update करें**
   - cPanel File Manager में जाएं
   - यहां जाएं: `/home/rsmmultilink/public_html/`
   - इस फाइल को edit करें: `.env`
   - ये लाइनें ढूंढें:
     ```
     DB_DATABASE=rsmmultilink_rsmupdate
     DB_USERNAME=rsmmultilink_rsmupdate
     DB_PASSWORD=rsmupdate@@
     ```
   - `rsmupdate@@` को cPanel से मिले सही password से बदलें
   - **फाइल को SAVE करें**

5. **Laravel Cache Clear करें** (cPanel Terminal या SSH से)
   ```bash
   cd /home/rsmmultilink/public_html
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

---

### स्टेप 2: MALWARE FILES डिलीट करें (जरूरी - 3 मिनट)

**Option A: Cleanup Script चलाएं (Recommended)**
```bash
cd /home/rsmmultilink/public_html
php cleanup-malware.php
```

**Option B: cPanel File Manager से Manual Delete करें**
1. cPanel File Manager में लॉगिन करें
2. यहां जाएं: `/home/rsmmultilink/public_html/`
3. ये फाइलें ढूंढकर **DELETE करें**:
   - `filefuns.php` ⚠️ **खतरनाक MALWARE**
   - `goods.php` ⚠️ **खतरनाक MALWARE**
   - `qwe.php` (अगर है तो)
   - `shell.php` (अगर है तो)
   - Root directory में कोई भी संदिग्ध .php files

4. File Manager में **Empty Trash** करें

---

### स्टेप 3: वेबसाइट चेक करें (2 मिनट)

1. **Status Page देखें**
   - विजिट करें: https://www.rsmmultilink.com/status.php
   - सभी checks के लिए green checkmarks दिखने चाहिए
   - अगर red errors हैं, तो page पर दिए instructions follow करें

2. **Database Connection Test करें**
   - विजिट करें: https://www.rsmmultilink.com/test-db.php
   - "✅ DATABASE CONNECTION SUCCESSFUL!" दिखना चाहिए
   - अगर failed है, तो स्टेप 1 फिर से करें

3. **Main Website देखें**
   - विजिट करें: https://www.rsmmultilink.com
   - Homepage properly load होना चाहिए
   - अगर अभी भी blank है, तो error logs check करें

---

### स्टेप 4: ERROR LOGS देखें (अगर website अभी भी काम नहीं कर रही)

**cPanel से:**
1. cPanel → Metrics → Errors में जाएं
2. Recent errors देखें

**File Manager से:**
1. खोलें: `/home/rsmmultilink/public_html/storage/logs/laravel.log`
2. File के नीचे latest errors देखें
3. अगर help चाहिए तो मुझे last 20 lines भेजें

---

### स्टेप 5: SECURITY CLEANUP (Website चालू होने के बाद)

1. **सभी Passwords बदलें**
   - cPanel password
   - FTP password
   - Database password (अगर अभी तक नहीं बदला)
   - SSH password

2. **Diagnostic Files Delete करें** (security के लिए)
   ```bash
   cd /home/rsmmultilink/public_html
   rm status.php
   rm test-db.php
   rm URGENT-FIX-DATABASE.txt
   rm FIX-STEPS-MANUAL.md
   rm FIX-STEPS-HINDI.md
   ```

3. **Backdoors Check करें**
   - हाल ही में upload की गई सभी files/images scan करें
   - public/uploads/ folder में suspicious .php files check करें
   - अगर available है तो cPanel virus scanner use करें

4. **.htaccess Protection Update करें** (optional but recommended)
   .htaccess के नीचे यह add करें:
   ```apache
   # Block malware patterns
   <FilesMatch "(filefuns|goods|shell|c99|r57|wso|alfa)\.php$">
       Order allow,deny
       Deny from all
   </FilesMatch>
   ```

---

## 📞 मदद चाहिए?

अगर सभी steps follow करने के बाद भी website काम नहीं कर रही:

1. **इनके Screenshots लें:**
   - status.php page का
   - test-db.php page का
   - जो भी error messages दिख रहे हैं

2. **मुझे भेजें:**
   - Screenshots
   - laravel.log की last 20 lines
   - बताएं किस step पर अटके हैं

---

## ⚡ QUICK CHECKLIST

- [ ] .env में database password ठीक किया
- [ ] Laravel cache clear किया
- [ ] filefuns.php delete किया
- [ ] goods.php delete किया
- [ ] status.php पर सब green दिख रहा है
- [ ] test-db.php successfully connect हो रहा है
- [ ] Website homepage properly load हो रहा है
- [ ] सभी passwords बदल दिए
- [ ] Diagnostic files delete कर दीं

---

**Last Updated:** 2026-06-11 06:00 UTC
**Git Commit:** 7c1c033
**Deployment Status:** ✅ सारा code webhook से deploy हो गया
**बाकी है:** Manual database fix + malware deletion
