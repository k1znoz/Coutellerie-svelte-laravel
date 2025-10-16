#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

cd services/coutellerie-laravel

# Run migrations
echo "� Running database migrations..."
php artisan migrate --force --no-interaction

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear

echo "🌟 Starting Laravel server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}