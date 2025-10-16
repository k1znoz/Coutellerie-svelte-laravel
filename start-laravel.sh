#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Run migrations
echo "📊 Running database migrations..."
php artisan migrate --force --no-interaction

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear

# Start Laravel server
echo "🌟 Starting Laravel server on port ${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}