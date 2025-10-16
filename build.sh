#!/bin/bash
set -e

echo "🚀 Starting Railway deployment build..."

# Variables
LARAVEL_PATH="services/coutellerie-laravel"
SVELTE_PATH="apps/coutellerie-svelte"

# 1. Build Svelte application
echo "📦 Building Svelte application..."
cd $SVELTE_PATH
npm ci --only=production
npm run build
cd ../../

# 2. Install Laravel dependencies
echo "📦 Installing Laravel dependencies..."
cd $LARAVEL_PATH
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# 3. Copy Svelte build to Laravel public directory
echo "📋 Copying Svelte build to Laravel..."
mkdir -p public/app
cp -r ../../$SVELTE_PATH/build/* public/app/

# 4. Laravel optimizations
echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 755 storage bootstrap/cache

# 6. Run database migrations (if database is available)
echo "🗄️ Running migrations..."
if [ "$APP_ENV" = "production" ]; then
    php artisan migrate --force --no-interaction
fi

echo "✅ Build completed successfully!"