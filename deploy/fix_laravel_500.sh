#!/bin/bash
set -e

BACKEND_DIR="/var/www/ai_video_platform/backend"

cd "$BACKEND_DIR"

echo "[1] Ensure runtime directories exist"
mkdir -p storage/logs bootstrap/cache
touch storage/logs/laravel.log

echo "[2] Fix ownership and permissions"
chown -R www-data:www-data /var/www/ai_video_platform
find "$BACKEND_DIR" -type f -exec chmod 644 {} \;
find "$BACKEND_DIR" -type d -exec chmod 755 {} \;
chmod -R 775 storage bootstrap/cache

echo "[3] Ensure .env exists"
if [ ! -s .env ]; then
  if [ -f .env.production ]; then
    cp .env.production .env
  elif [ -f .env.example ]; then
    cp .env.example .env
  fi
fi

echo "[4] Ensure APP_KEY exists"
if ! grep -q '^APP_KEY=base64:' .env; then
  php artisan key:generate --force
fi

echo "[5] Clear caches"
php artisan optimize:clear || true
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "[6] Basic artisan health"
php artisan about || true

echo "[7] Last Laravel log lines"
tail -n 80 storage/logs/laravel.log || true

echo "[8] Restart services"
systemctl restart php8.3-fpm
systemctl restart nginx

echo "[9] Local HTTP probe"
curl -I http://127.0.0.1 || true
