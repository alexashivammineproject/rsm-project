#!/bin/bash

# 🚀 ONE-COMMAND DEPLOYMENT FOR LIVE SERVER
# Copy and paste everything below (starting from 'cd') into your live server terminal

cd /home/rsmmultilink/public_html && git fetch origin && git reset --hard origin/main && php artisan migrate --force && php artisan cache:clear && php artisan config:clear && php artisan view:clear && echo "✅ DEPLOYMENT SUCCESSFUL - Latest commit:" && git log --oneline -1

# If that doesn't work, run commands one by one:
# 1. cd /home/rsmmultilink/public_html
# 2. git fetch origin
# 3. git reset --hard origin/main
# 4. php artisan migrate --force
# 5. php artisan cache:clear
# 6. php artisan config:clear
# 7. php artisan view:clear
