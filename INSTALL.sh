#!/bin/bash
# =====================================================
#   MAHARANA TRAVELS — Complete Installation Script
#   Run this file OR copy-paste commands one by one
# =====================================================

# ─────────────────────────────────────────────────────
# STEP 1 — Install PHP (if not installed)
# ─────────────────────────────────────────────────────
# Ubuntu / Debian:
sudo apt update
sudo apt install -y php8.2 php8.2-cli php8.2-mbstring php8.2-xml \
  php8.2-bcmath php8.2-curl php8.2-mysql php8.2-zip php8.2-tokenizer \
  php8.2-json php8.2-pdo unzip curl git

# Check PHP version (must be 8.2+)
php -v

# ─────────────────────────────────────────────────────
# STEP 2 — Install Composer (if not installed)
# ─────────────────────────────────────────────────────
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version

# ─────────────────────────────────────────────────────
# STEP 3 — Install Node.js 18+ (if not installed)
# ─────────────────────────────────────────────────────
# Using NVM (recommended):
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash
source ~/.bashrc
nvm install 20
nvm use 20
node -v
npm -v

# OR using NodeSource (Ubuntu):
# curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
# sudo apt install -y nodejs

# ─────────────────────────────────────────────────────
# STEP 4 — Install MySQL (if not installed)
# ─────────────────────────────────────────────────────
sudo apt install -y mysql-server
sudo systemctl start mysql
sudo systemctl enable mysql

# Secure MySQL and create database:
sudo mysql -u root -p -e "CREATE DATABASE maharana_travels CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -u root -p -e "CREATE USER 'maharana'@'localhost' IDENTIFIED BY 'secret123';"
sudo mysql -u root -p -e "GRANT ALL PRIVILEGES ON maharana_travels.* TO 'maharana'@'localhost';"
sudo mysql -u root -p -e "FLUSH PRIVILEGES;"

# ─────────────────────────────────────────────────────
# STEP 5 — Extract & Enter Project Folder
# ─────────────────────────────────────────────────────
unzip maharana-laravel-vue.zip
cd maharana

# ─────────────────────────────────────────────────────
# STEP 6 — Install PHP / Laravel Dependencies
# ─────────────────────────────────────────────────────
composer install

# ─────────────────────────────────────────────────────
# STEP 7 — Environment Setup
# ─────────────────────────────────────────────────────
cp .env.example .env
php artisan key:generate

# Now edit .env with your DB credentials:
# DB_DATABASE=maharana_travels
# DB_USERNAME=maharana
# DB_PASSWORD=secret123

# Quick way to edit (nano):
nano .env

# ─────────────────────────────────────────────────────
# STEP 8 — Run Database Migrations
# ─────────────────────────────────────────────────────
php artisan migrate

# ─────────────────────────────────────────────────────
# STEP 9 — Install Node.js / Vue.js Dependencies
# ─────────────────────────────────────────────────────
npm install

# ─────────────────────────────────────────────────────
# STEP 10 — Create Required Storage Directories
# ─────────────────────────────────────────────────────
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
chmod -R 775 storage bootstrap/cache
chown -R $USER:www-data storage bootstrap/cache

php artisan storage:link

# ─────────────────────────────────────────────────────
# STEP 11 — RUN (Development Mode)
# Open TWO terminals and run one command in each:
# ─────────────────────────────────────────────────────

# === Terminal 1 — Laravel backend server ===
php artisan serve
# Runs at: http://localhost:8000

# === Terminal 2 — Vite frontend (hot reload) ===
npm run dev
# Vite serves assets with HMR

# Open browser: http://localhost:8000 ✅

# ─────────────────────────────────────────────────────
# STEP 12 — PRODUCTION BUILD (when deploying)
# ─────────────────────────────────────────────────────
npm run build                    # Compile & minify JS/CSS into public/build/
php artisan config:cache         # Cache config
php artisan route:cache          # Cache routes
php artisan view:cache           # Cache blade views
php artisan optimize             # All caches at once

# ─────────────────────────────────────────────────────
# USEFUL ARTISAN COMMANDS
# ─────────────────────────────────────────────────────
php artisan route:list           # See all registered routes
php artisan migrate:fresh        # Drop all tables and re-migrate
php artisan migrate:fresh --seed # Re-migrate + run seeders
php artisan optimize:clear       # Clear all caches
php artisan config:clear         # Clear config cache only
php artisan cache:clear          # Clear app cache
php artisan view:clear           # Clear compiled views

# ─────────────────────────────────────────────────────
# WINDOWS USERS (PowerShell / Command Prompt)
# ─────────────────────────────────────────────────────
# All the same commands work EXCEPT:
#   - Use `copy` instead of `cp`
#   - Use `mkdir` for directories
#   - chmod / chown are not needed
#
# Install Composer from: https://getcomposer.org/Composer-Setup.exe
# Install Node from:     https://nodejs.org
# Install XAMPP from:    https://www.apachefriends.org  (PHP + MySQL together)
# OR use Laravel Herd:   https://herd.laravel.com       (easiest on Windows/Mac)

# ─────────────────────────────────────────────────────
# MAC USERS (Homebrew)
# ─────────────────────────────────────────────────────
# brew install php composer node mysql
# brew services start mysql
# Then follow from STEP 5 above

# ─────────────────────────────────────────────────────
# EASIEST OPTION: Laravel Herd (Windows & Mac)
# ─────────────────────────────────────────────────────
# 1. Download Laravel Herd from https://herd.laravel.com
# 2. Install it — PHP, Composer, Node all come bundled
# 3. Open terminal, go to your project folder
# 4. Run: composer install && npm install && php artisan migrate
# 5. Open: http://maharana.test  (Herd auto-serves it)
