#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Navigate to Laravel directory
cd services/coutellerie-laravel

# Copy production environment file if in production
if [ ! -f ".env" ] && [ -f ".env.production" ]; then
    echo "🔧 Setting up production environment..."
    cp .env.production .env
fi

# Always ensure Laravel dependencies are installed
echo "📦 Installing/verifying Laravel dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Verify that vendor/autoload.php exists
if [ ! -f "vendor/autoload.php" ]; then
    echo "❌ Error: vendor/autoload.php not found after composer install"
    echo "Current directory: $(pwd)"
    echo "Contents of current directory:"
    ls -la
    echo "Contents of vendor directory (if exists):"
    ls -la vendor/ 2>/dev/null || echo "vendor directory does not exist"
    exit 1
fi

echo "✅ Laravel dependencies successfully installed"

# Copy Svelte build to Laravel public (if exists)
if [ -d "../apps/coutellerie-svelte/build" ]; then
    echo "📋 Copying Svelte build to Laravel public..."
    mkdir -p public/app
    cp -r ../apps/coutellerie-svelte/build/* public/app/ 2>/dev/null || true
fi

# Wait a bit for MySQL service to be ready
echo "🗄️ Waiting for MySQL database to be ready..."
sleep 5

# Run migrations
echo "📊 Running database migrations..."
php artisan migrate --force --no-interaction

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear

echo "🌟 Starting Laravel server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}