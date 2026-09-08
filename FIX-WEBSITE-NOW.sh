#!/bin/bash
# 🚨 EMERGENCY WEBSITE FIX - RUN THIS ON LIVE SERVER NOW

# Copy and paste everything below into your live server terminal

cd /home/rsmmultilink/public_html && git reset --hard 87af9db && php artisan cache:clear && git fetch origin && git reset --hard 8443f88 && php artisan migrate --force && php artisan cache:clear && php artisan config:clear && php artisan view:clear && echo "✅ WEBSITE FIXED - DEPLOYMENT COMPLETE"

# Expected output: ✅ WEBSITE FIXED - DEPLOYMENT COMPLETE

# After that, verify:
# 1. curl https://rsmmultilink.com/
# 2. tail /home/rsmmultilink/public_html/storage/logs/laravel.log
