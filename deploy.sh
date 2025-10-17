#!/bin/bash

# Railway deployment script for Laravel - Simple approach
echo "🚀 Railway Laravel deployment..."

# Install PHP extensions using docker-php-ext-install (Railway compatible)
echo "� Installing PHP extensions for Railway..."
export DEBIAN_FRONTEND=noninteractive
apt-get update -qq > /dev/null 2>&1

# Install required development libraries
apt-get install -y -qq \
    libicu-dev \
    libzip-dev \
    default-mysql-client \
    libmysqlclient-dev > /dev/null 2>&1

# Try to get MySQL extensions working
echo "🔧 Attempting to install MySQL extensions..."

# Method 1: Try system packages first
apt-get install -y -qq php-mysql php-pdo-mysql > /dev/null 2>&1 || echo "System packages failed"

# Method 2: Enable any existing extensions
echo "extension=pdo_mysql" > /usr/local/etc/php/conf.d/pdo_mysql.ini || true
echo "extension=mysqli" > /usr/local/etc/php/conf.d/mysqli.ini || true

# Method 3: Last resort - try compilation with minimal deps
docker-php-ext-install pdo_mysql > /dev/null 2>&1 || echo "pdo_mysql compilation failed"
docker-php-ext-install mysqli > /dev/null 2>&1 || echo "mysqli compilation failed"

# Change to Laravel directory
cd services/coutellerie-laravel || exit 1

# Verify extensions are properly loaded
echo "🔍 Checking PHP extensions..."
php -r "echo 'PDO: ' . (extension_loaded('pdo') ? '✅' : '❌') . PHP_EOL;"
php -r "echo 'PDO MySQL: ' . (extension_loaded('pdo_mysql') ? '✅' : '❌') . PHP_EOL;"
php -r "echo 'MySQLi: ' . (extension_loaded('mysqli') ? '✅' : '❌') . PHP_EOL;"
php -r "echo 'Available PDO drivers: ' . implode(', ', PDO::getAvailableDrivers()) . PHP_EOL;" 2>/dev/null || echo "PDO not available"

# Install dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts --quiet || {
    composer update --no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts --quiet || exit 1
}

echo "🔧 Regenerating Composer autoload..."
composer dump-autoload --optimize --quiet

# Run package discovery
echo "🔍 Running package discovery..."
php artisan package:discover --ansi || echo "⚠️ Package discovery failed"
php artisan config:cache --quiet || echo "⚠️ Config cache failed"

echo "🚀 Bootstrapping Laravel application..."
php artisan about --only=environment || echo "⚠️ Laravel bootstrap check failed"

# Setup environment with Railway MySQL
echo "⚙️ Setting up environment with MySQL..."

# Use existing .env file (already configured with Railway variables)
echo "🔄 Using existing .env configuration..."
echo "✅ Environment configuration ready"

# Test database connection before migrations
echo "🔗 Testing database connection..."
if php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database: ✅';" 2>/dev/null; then
    echo "🗄️ Running migrations..."
    php artisan migrate --force || echo "⚠️ Migrations failed"
    
    echo "� Running seeders..."
    php artisan db:seed --force || echo "⚠️ Seeders failed"
    
    echo "🎨 Setting up Filament..."
    php artisan filament:install --panels --force --quiet || echo "⚠️ Filament install failed"
    
    echo "📦 Publishing Filament auth views and resources..."
    php artisan vendor:publish --tag=filament-config --force || echo "⚠️ Filament config publish failed"
    php artisan vendor:publish --tag=filament-views --force || echo "⚠️ Filament views publish failed"
    
    php artisan filament:assets --quiet || echo "⚠️ Filament assets failed"
    
    echo "🔧 Publishing Filament assets and clearing cache..."
    php artisan vendor:publish --tag=filament-assets --force || echo "⚠️ Publishing assets failed"
    php artisan view:clear || echo "⚠️ View clear failed"
    php artisan config:clear || echo "⚠️ Config clear failed"
    php artisan route:clear || echo "⚠️ Route clear failed"
    php artisan cache:clear || echo "⚠️ Cache clear failed"
    php artisan optimize:clear || echo "⚠️ Optimize clear failed"
    
    echo "🔍 Listing available routes..."
    php artisan route:list || echo "⚠️ Route list failed"
    
    echo "🔍 Checking Filament installation..."
    php -r "
        require_once 'vendor/autoload.php';
        echo 'Filament Panel loaded: ' . (class_exists('Filament\\Panel') ? '✅' : '❌') . PHP_EOL;
        echo 'Filament Facades loaded: ' . (class_exists('Filament\\Facades\\Filament') ? '✅' : '❌') . PHP_EOL;
        echo 'AdminPanelProvider loaded: ' . (class_exists('App\\Providers\\Filament\\AdminPanelProvider') ? '✅' : '❌') . PHP_EOL;
    "
    
    echo "🔍 Testing Filament routes registration..."
    php artisan route:list --path=admin | head -10 || echo "⚠️ Filament routes not found"
else
    echo "❌ Database connection failed, skipping migrations"
fi

# Cache config
echo "⚡ Optimizing Laravel for production..."
php artisan config:clear || echo "⚠️ Config clear failed"
php artisan route:clear || echo "⚠️ Route clear failed"
php artisan view:clear || echo "⚠️ View clear failed"
php artisan cache:clear || echo "⚠️ Cache clear failed"

# Ne pas cacher les routes en production pour Filament
php artisan config:cache > /dev/null 2>&1
# php artisan route:cache > /dev/null 2>&1  # Désactivé pour Filament
php artisan view:cache > /dev/null 2>&1 || echo "⚠️ View cache failed"

# Start server
echo "🌟 Starting server on port ${PORT:-8000}..."
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"