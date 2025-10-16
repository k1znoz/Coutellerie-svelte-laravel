#!/bin/bash
set -e

echo "🚀 Starting Railway application..."

# Navigate to Laravel directory
cd services/coutellerie-laravel

# Copy environment file
if [ -f "../../.env.railway" ]; then
    cp ../../.env.railway .env
    echo "✅ Environment file copied"
fi

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --no-interaction
fi

# Clear all caches to be sure
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Run migrations if needed
php artisan migrate --force --no-interaction

# Start the application
echo "🌟 Starting Laravel application on port ${PORT:-8000}"
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}