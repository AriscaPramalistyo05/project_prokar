#!/usr/bin/env bash
set -e

echo "🚀 Memulai deployment Prokar Elektronik..."

# 1. Masuk mode maintenance
php artisan down || true

# 2. Update dependensi
composer install --no-dev --optimize-autoloader --no-interaction

# 3. Jalankan migrasi database
php artisan migrate --force

# 4. Clear & re-cache konfigurasi, rute, dan view
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Storage symlink
php artisan storage:link || true

# 6. Generate sitemap
php artisan sitemap:generate

# 7. Restart queue worker jika menggunakan supervisor/queue
php artisan queue:restart || true

# 8. Keluar mode maintenance
php artisan up

echo "✅ Deployment selesai sukses!"
