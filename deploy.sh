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

# Use docker-php-ext-install which is Railway/Docker compatible
echo "📦 Installing extensions via docker-php-ext-install..."
docker-php-ext-install pdo_mysql > /dev/null 2>&1 || echo "⚠️ pdo_mysql failed"
docker-php-ext-install mysqli > /dev/null 2>&1 || echo "⚠️ mysqli failed"  
docker-php-ext-install intl > /dev/null 2>&1 || echo "⚠️ intl failed"
docker-php-ext-install zip > /dev/null 2>&1 || echo "⚠️ zip failed"

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

# Run package discovery
php artisan package:discover --ansi > /dev/null 2>&1 || echo "⚠️ Package discovery failed"

# Setup environment
echo "⚙️ Setting up environment..."
if [ -f .env.production ]; then
    cp .env.production .env
    
    # Use best available database URL
    if [ -n "$DATABASE_URL" ]; then
        sed -i "s|\${DATABASE_URL:-\${MYSQL_PUBLIC_URL}}|$DATABASE_URL|g" .env
    elif [ -n "$MYSQL_URL" ]; then
        sed -i "s|\${DATABASE_URL:-\${MYSQL_PUBLIC_URL}}|$MYSQL_URL|g" .env  
    elif [ -n "$MYSQL_PUBLIC_URL" ]; then
        sed -i "s|\${DATABASE_URL:-\${MYSQL_PUBLIC_URL}}|$MYSQL_PUBLIC_URL|g" .env
    fi
fi

# Generate app key
if [ -z "$APP_KEY" ]; then
    APP_KEY="base64:$(openssl rand -base64 32)"
    echo "APP_KEY=$APP_KEY" >> .env
fi

# Test database connection before migrations
echo "🔗 Testing database connection..."
if php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database: ✅';" 2>/dev/null; then
    echo "🗄️ Running migrations..."
    php artisan migrate --force || echo "⚠️ Migrations failed"
else
    echo "❌ Database connection failed, skipping migrations"
fi

# Cache config
php artisan config:cache > /dev/null 2>&1

# Start server
echo "🌟 Starting server on port ${PORT:-8000}..."
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"