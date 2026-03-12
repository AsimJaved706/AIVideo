# VideoAI Studio — AWS EC2 Deployment Guide

## EC2 Instance Requirements

| Setting       | Recommended                              |
|---------------|------------------------------------------|
| AMI           | Ubuntu 22.04 LTS or 24.04 LTS           |
| Instance type | t3.medium (2 vCPU, 4 GB RAM) minimum     |
| Storage       | 30 GB gp3 SSD (50+ GB if storing videos)|
| Security Group | Port 22 (SSH), 80 (HTTP), 443 (HTTPS) |

---

## Step 1 — Launch EC2 & SSH In

1. Go to **AWS Console → EC2 → Launch Instance**
2. Choose **Ubuntu 22.04 LTS**, instance type **t3.medium**
3. Create or select a key pair — download the `.pem` file
4. Under Security Group, open ports **22, 80, 443**
5. Launch, then connect:

```bash
chmod 400 your-key.pem
ssh -i your-key.pem ubuntu@YOUR_EC2_PUBLIC_IP
```

---

## Step 2 — Upload the Project

**Option A — From GitHub (recommended):**
```bash
sudo mkdir -p /var/www/ai_video_platform
sudo chown ubuntu:ubuntu /var/www/ai_video_platform
cd /var/www/ai_video_platform
git clone https://github.com/AsimJaved706/AIVideo.git .
```

**Option B — Upload via SCP from your Windows machine:**
```powershell
# Run in PowerShell on your Windows machine
scp -i your-key.pem -r "C:\Users\WT\.gemini\antigravity\scratch\ai_video_platform" ubuntu@YOUR_EC2_IP:/var/www/
```

---

## Step 3 — Run the Automated Setup Script

```bash
cd /var/www/ai_video_platform
sudo bash deploy/setup.sh
```

This installs and configures:
- PHP 8.3 + php-fpm
- Composer
- Node.js 22 + npm
- MySQL 8 (creates DB `videoai` and user `videoai_user`)
- Python 3.11 + virtualenv + all pip packages
- Nginx (configured for Laravel)
- Systemd services for PHP queue worker and Python FastAPI worker

---

## Step 4 — Configure Environment Files

### Laravel Backend
```bash
sudo nano /var/www/ai_video_platform/backend/.env
```

Required changes:
```dotenv
APP_URL=http://YOUR_EC2_IP_OR_DOMAIN
APP_KEY=                    # Run: php artisan key:generate
DB_PASSWORD=ChangeMe_Strong_Password_123   # Must match setup.sh DB_PASS
```

Generate app key:
```bash
cd /var/www/ai_video_platform/backend
sudo -u www-data php artisan key:generate
sudo -u www-data php artisan config:cache
```

### Python Worker
```bash
cp /var/www/ai_video_platform/worker/.env.example \
   /var/www/ai_video_platform/worker/.env
sudo nano /var/www/ai_video_platform/worker/.env
```

> **Note:** API keys (ElevenLabs, Runway, Gemini) are stored per-user in the app Settings. You only need to set them here if you want server-wide defaults.

---

## Step 5 — Verify Services Are Running

```bash
# Check Nginx
sudo systemctl status nginx

# Check PHP-FPM
sudo systemctl status php8.3-fpm

# Check Laravel queue worker
sudo systemctl status videoai-queue

# Check Python FastAPI worker
sudo systemctl status videoai-worker

# View Python worker logs
sudo journalctl -u videoai-worker -f

# View queue worker logs
sudo journalctl -u videoai-queue -f
```

Test the worker API is up:
```bash
curl http://127.0.0.1:8001/docs
```

---

## Step 6 — Run Migrations & Seed

```bash
cd /var/www/ai_video_platform/backend
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan db:seed --force
```

---

## Step 7 — (Optional) Set Up SSL with Let's Encrypt

Requires a domain name pointed to your EC2 IP.

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

Then update your `.env`:
```dotenv
APP_URL=https://yourdomain.com
```

And re-cache config:
```bash
sudo -u www-data php artisan config:cache
```

---

## Common Commands

| Task                          | Command                                                      |
|-------------------------------|--------------------------------------------------------------|
| Restart Python worker         | `sudo systemctl restart videoai-worker`                     |
| Restart queue worker          | `sudo systemctl restart videoai-queue`                      |
| Reload Nginx                  | `sudo systemctl reload nginx`                               |
| View app logs                 | `sudo tail -f /var/www/ai_video_platform/backend/storage/logs/laravel.log` |
| View Nginx error log          | `sudo tail -f /var/log/nginx/videoai_error.log`             |
| Re-run migrations             | `sudo -u www-data php artisan migrate --force`              |
| Clear all caches              | `sudo -u www-data php artisan optimize:clear`               |
| Deploy code update (git)      | `git pull && sudo -u www-data php artisan migrate --force && sudo -u www-data php artisan optimize && npm run build` |

---

## Architecture on EC2

```
Internet
    │
    ▼
Nginx :80/:443
    │ serves /public  (Laravel)
    │
    ├─► PHP-FPM 8.3        ← Laravel app
    │       │
    │       └─► MySQL 8    ← database (users, campaigns, queue jobs)
    │
    ├─► videoai-queue      ← Laravel Queue Worker (dispatches jobs)
    │       │
    │       └─► HTTP POST → 127.0.0.1:8001
    │
    └─► videoai-worker     ← Python FastAPI (Gemini / ElevenLabs / Runway)
            │
            ├─► Google Gemini API  (script generation)
            ├─► ElevenLabs API     (voice synthesis)
            ├─► Runway ML API      (video generation)
            └─► AWS S3             (optional media storage)
```
