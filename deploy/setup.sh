#!/bin/bash
# ============================================================
# VideoAI Studio — AWS EC2 Ubuntu 22.04 / 24.04 Setup Script
# Run as: sudo bash setup.sh
# ============================================================
set -e

PROJECT_DIR="/var/www/ai_video_platform"
BACKEND_DIR="$PROJECT_DIR/backend"
WORKER_DIR="$PROJECT_DIR/worker"
DOMAIN=""          # Set your domain or EC2 public IP, e.g. "54.123.45.67"
DB_NAME="videoai"
DB_USER="videoai_user"
DB_PASS="ChangeMe_Strong_Password_123"
PHP_VER="8.3"

echo "============================================"
echo " VideoAI Studio — EC2 Ubuntu Setup"
echo "============================================"

# ── 1. System update ─────────────────────────────────────────
echo "[1/10] Updating system packages..."
apt-get update -y && apt-get upgrade -y
apt-get install -y curl wget git unzip software-properties-common \
    apt-transport-https ca-certificates gnupg lsb-release ffmpeg

# ── 2. PHP 8.3 + extensions ──────────────────────────────────
echo "[2/10] Installing PHP $PHP_VER..."
add-apt-repository ppa:ondrej/php -y
apt-get update -y
apt-get install -y \
    php${PHP_VER} php${PHP_VER}-cli php${PHP_VER}-fpm \
    php${PHP_VER}-mysql php${PHP_VER}-sqlite3 php${PHP_VER}-pdo \
    php${PHP_VER}-mbstring php${PHP_VER}-xml php${PHP_VER}-bcmath \
    php${PHP_VER}-curl php${PHP_VER}-zip php${PHP_VER}-gd \
    php${PHP_VER}-tokenizer php${PHP_VER}-intl php${PHP_VER}-redis

# ── 3. Composer ───────────────────────────────────────────────
echo "[3/10] Installing Composer..."
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# ── 4. Node.js 22 + npm ───────────────────────────────────────
echo "[4/10] Installing Node.js 22..."
curl -fsSL https://deb.nodesource.com/setup_22.x | bash -
apt-get install -y nodejs

# ── 5. MySQL 8 ────────────────────────────────────────────────
echo "[5/10] Installing MySQL 8..."
apt-get install -y mysql-server
systemctl enable --now mysql

mysql -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\`;"
mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
mysql -e "GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"
echo "    ✓ Database '${DB_NAME}' and user '${DB_USER}' created."

# ── 6. Python 3.11 + virtualenv ───────────────────────────────
echo "[6/10] Installing Python 3.11 + venv..."
apt-get install -y python3.11 python3.11-venv python3.11-dev python3-pip
python3.11 -m venv "$WORKER_DIR/venv"
source "$WORKER_DIR/venv/bin/activate"
pip install --upgrade pip
pip install -r "$WORKER_DIR/requirements.txt"
deactivate

# ── 7. Nginx ──────────────────────────────────────────────────
echo "[7/10] Installing Nginx..."
apt-get install -y nginx
systemctl enable nginx

# ── 8. Laravel backend setup ──────────────────────────────────
echo "[8/10] Setting up Laravel backend..."
cd "$BACKEND_DIR"

composer install --no-dev --optimize-autoloader

if [ ! -f ".env" ]; then
    cp .env.production .env
fi

php artisan key:generate --force
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

npm ci
npm run build

chown -R www-data:www-data "$BACKEND_DIR"
chmod -R 755 "$BACKEND_DIR"
chmod -R 775 "$BACKEND_DIR/storage"
chmod -R 775 "$BACKEND_DIR/bootstrap/cache"

# ── 9. Nginx virtual host ─────────────────────────────────────
echo "[9/10] Configuring Nginx..."
cp "$PROJECT_DIR/deploy/nginx.conf" /etc/nginx/sites-available/videoai
ln -sf /etc/nginx/sites-available/videoai /etc/nginx/sites-enabled/videoai
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx

# ── 10. Systemd services ──────────────────────────────────────
echo "[10/10] Enabling systemd services..."
cp "$PROJECT_DIR/deploy/videoai-worker.service" /etc/systemd/system/
cp "$PROJECT_DIR/deploy/videoai-queue.service"  /etc/systemd/system/

systemctl daemon-reload
systemctl enable --now videoai-worker
systemctl enable --now videoai-queue

# ── Done ──────────────────────────────────────────────────────
echo ""
echo "============================================"
echo " ✅  Setup complete!"
echo "============================================"
echo " Laravel:  http://${DOMAIN:-<your-ec2-ip>}"
echo " Worker:   http://127.0.0.1:8001 (internal)"
echo ""
echo " ⚠️  Next steps:"
echo "   1. Edit $BACKEND_DIR/.env and fill in all API keys"
echo "   2. Edit $WORKER_DIR/.env and fill in all API keys"
echo "   3. (Optional) Run: sudo certbot --nginx -d yourdomain.com"
echo "============================================"
