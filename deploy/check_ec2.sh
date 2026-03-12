#!/bin/bash
set -e

echo "=== EC2 Web Check ==="
echo

echo "[1] Public listeners"
sudo ss -ltnp | grep -E ':80|:443|:8001' || true

echo

echo "[2] Nginx status"
sudo systemctl --no-pager --full status nginx | sed -n '1,20p' || true

echo

echo "[3] PHP-FPM status"
sudo systemctl --no-pager --full status php8.3-fpm | sed -n '1,20p' || true

echo

echo "[4] Nginx config test"
sudo nginx -t || true

echo

echo "[5] Enabled sites"
ls -la /etc/nginx/sites-enabled || true

echo

echo "[6] App files"
ls -la /var/www/ai_video_platform/backend/public || true

echo

echo "[7] Firewall"
sudo ufw status || true

echo

echo "[8] Local HTTP test"
curl -I http://127.0.0.1 || true

echo

echo "[9] Nginx logs"
sudo tail -n 30 /var/log/nginx/error.log || true
sudo tail -n 30 /var/log/nginx/videoai_error.log || true
