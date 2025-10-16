#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Install Laravel dependencies if not already done
if [ ! -d "services/coutellerie-laravel/vendor" ]; then
    echo "📦 Installing Laravel dependencies..."
    cd services/coutellerie-laravel
    composer install --no-dev --optimize-autoloader --no-interaction
    cd ..
fi

cd services/coutellerie-laravel

# Copy Svelte build to Laravel public (if exists)
if [ -d "../apps/coutellerie-svelte/build" ]; then
    echo "📋 Copying Svelte build to Laravel public..."
    mkdir -p public/app
    cp -r ../apps/coutellerie-svelte/build/* public/app/ 2>/dev/null || true
fi

# Run migrations
echo "📊 Running database migrations..."
php artisan migrate --force --no-interaction

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear

echo "🌟 Starting Laravel server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}