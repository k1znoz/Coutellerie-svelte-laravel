#!/bin/bash
set -e

echo "🚀 Starting Coutellerie application..."

# Install Laravel dependencies if needed
if [ ! -d "services/coutellerie-laravel/vendor" ]; then
    echo "📦 Installing Laravel dependencies..."
    cd services/coutellerie-laravel
    composer install --no-dev --optimize-autoloader --no-interaction
    cd ../..
fi

# Start Laravel
echo "🌟 Starting Laravel server..."
cd services/coutellerie-laravel
php artisan migrate --force --no-interaction
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}