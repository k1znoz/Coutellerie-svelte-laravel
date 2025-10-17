#!/bin/bash

# Railway deployment script for Laravel
echo "🚀 Railway Laravel deployment..."

# Install PHP extensions with verification
echo "🔧 Installing PHP extensions..."
export DEBIAN_FRONTEND=noninteractive
apt-get update -qq > /dev/null 2>&1
apt-get install -y -qq libicu-dev libzip-dev default-mysql-client libmysqlclient-dev > /dev/null 2>&1

# Install required PHP extensions one by one with verification
echo "📦 Installing PDO and MySQL extensions..."
docker-php-ext-install pdo || echo "⚠️ PDO installation failed"
docker-php-ext-install pdo_mysql || echo "⚠️ PDO MySQL installation failed"
docker-php-ext-install mysqli || echo "⚠️ MySQLi installation failed"
docker-php-ext-install intl || echo "⚠️ Intl installation failed"
docker-php-ext-install zip || echo "⚠️ Zip installation failed"

# Verify extensions are loaded
echo "🔍 Verifying PHP extensions..."
php -r "echo 'PDO: ' . (extension_loaded('pdo') ? '✅' : '❌') . PHP_EOL;"
php -r "echo 'PDO MySQL: ' . (extension_loaded('pdo_mysql') ? '✅' : '❌') . PHP_EOL;"
php -r "echo 'MySQLi: ' . (extension_loaded('mysqli') ? '✅' : '❌') . PHP_EOL;"

# Test MySQL PDO specifically
echo "🔧 Testing MySQL PDO driver availability..."
php -r "try { echo 'Available drivers: ' . implode(', ', PDO::getAvailableDrivers()) . PHP_EOL; } catch(Exception \$e) { echo 'PDO Error: ' . \$e->getMessage() . PHP_EOL; }"

# Change to Laravel directory
cd services/coutellerie-laravel || exit 1

# Install dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts --quiet || {
    echo "⚠️ Composer install failed, trying update..."
    composer update --no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts --quiet || exit 1
}

# Run essential post-install tasks
echo "🔍 Running package discovery..."
php artisan package:discover --ansi > /dev/null 2>&1 || echo "⚠️ Package discovery failed"

# Setup environment
echo "⚙️ Configuring environment..."
if [ -f .env.production ]; then
    cp .env.production .env
    
    # Use the best available database URL
    if [ -n "$DATABASE_URL" ]; then
        sed -i "s|\${DATABASE_URL:-\${MYSQL_PUBLIC_URL}}|$DATABASE_URL|g" .env
    elif [ -n "$MYSQL_URL" ]; then
        sed -i "s|\${DATABASE_URL:-\${MYSQL_PUBLIC_URL}}|$MYSQL_URL|g" .env  
    elif [ -n "$MYSQL_PUBLIC_URL" ]; then
        sed -i "s|\${DATABASE_URL:-\${MYSQL_PUBLIC_URL}}|$MYSQL_PUBLIC_URL|g" .env
    fi
fi

# Generate app key if needed
if [ -z "$APP_KEY" ]; then
    APP_KEY="base64:$(openssl rand -base64 32)"
    echo "APP_KEY=$APP_KEY" >> .env
fi

# Verify database connection before migrations
echo "🔗 Testing database connection..."
php -r "
try {
    \$config = require 'config/database.php';
    \$default = \$config['connections'][\$config['default']] ?? \$config['connections']['mysql'];
    echo 'Database config loaded successfully' . PHP_EOL;
    
    if (isset(\$_ENV['DATABASE_URL']) || isset(\$_ENV['MYSQL_URL']) || isset(\$_ENV['MYSQL_PUBLIC_URL'])) {
        echo 'Database URL found in environment' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Config error: ' . \$e->getMessage() . PHP_EOL;
}
" || echo "⚠️ Database config test failed"

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force || echo "⚠️ Migrations failed"

# Cache config for production
php artisan config:cache > /dev/null 2>&1

# Start server
echo "🌟 Starting server on port ${PORT:-8000}..."
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"