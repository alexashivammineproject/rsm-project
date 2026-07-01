# 🔒 MAKE GITHUB REPOSITORY PRIVATE

## STEP 1: Open Repository Settings

1. Go to: https://github.com/rsmmultilinkupdate-crypto/rsm-project
2. Click on **Settings** tab (top right)
3. Scroll down to **Danger Zone** (bottom of page)
4. Click **Change repository visibility**
5. Select **Make private**
6. Type the repository name to confirm: `rsmmultilinkupdate-crypto/rsm-project`
7. Click **I understand, change repository visibility**

✅ **DONE!** Your repository is now PRIVATE.

---

## 🚀 SERVER DEPLOYMENT COMMANDS

### OPTION 1: Manual Deploy via Webhook (RECOMMENDED)

Run this command on your **cPanel Terminal** or SSH:

```bash
curl "https://rsmmultilink.com/webhook.php?key=rsm123"
```

### OPTION 2: Full Deploy with Cache Clear

```bash
cd /home/rsmmultilink/public_html && \
curl "https://rsmmultilink.com/webhook.php?key=rsm123" && \
sleep 5 && \
php artisan config:clear && \
php artisan cache:clear && \
php artisan view:clear && \
echo "✓ Deployment Complete!"
```

---

## 📝 DEPLOYMENT LOG

Check deployment status:

```bash
tail -100 /home/rsmmultilink/public_html/deployment.log
```

---

## ⚡ AUTOMATIC DEPLOYMENT SETUP

### GitHub Actions (Future Enhancement)

Will add GitHub Actions to automatically trigger webhook on every push to main branch.

**For now:** After every git push, manually run the webhook URL in your browser or cPanel terminal.

---

## 🔐 WEBHOOK SECURITY

**Current Security:**
- ✅ URL key protection: `?key=rsm123`
- ✅ Malware cleanup on every deploy
- ✅ .env file protection (never overwritten)
- ✅ Storage/uploads preserved

**Webhook URL:** `https://rsmmultilink.com/webhook.php?key=rsm123`

**Change Security Key:** Edit `webhook.php` line 9 to change `rsm123` to your custom key.

---

## 📂 FILES NEVER OVERWRITTEN BY DEPLOYMENT

- `.env` (database credentials safe)
- `storage/app/public/images/*` (product images)
- `storage/app/public/pdf/*` (uploaded PDFs)
- `storage/logs/*` (log files)

---

## 🔄 WORKFLOW

```
Local Code Changes
      ↓
git add .
git commit -m "description"
git push origin main
      ↓
Run webhook URL
      ↓
✅ Website Updated!
```
