#!/bin/bash

# Railway deployment script for Laravel
echo "🚀 Starting Railway deployment..."

# Install PHP extensions using Docker PHP extension installer
echo "🔧 Installing PHP extensions via docker-php-ext-install..."
export DEBIAN_FRONTEND=noninteractive

# First update package list and install required system packages
apt-get update -qq
apt-get install -y -qq libicu-dev libzip-dev libxml2-dev libcurl4-openssl-dev libpng-dev libjpeg-dev libfreetype6-dev default-mysql-client libonig-dev

# Install PHP extensions using docker-php-ext-install
docker-php-ext-install -j$(nproc) \
    intl \
    zip \
    xml \
    curl \
    mbstring \
    gd \
    pdo \
    pdo_mysql \
    mysqli

# Configure GD extension with freetype and jpeg support
docker-php-ext-configure gd --with-freetype --with-jpeg
docker-php-ext-install -j$(nproc) gd

# Change to Laravel directory
cd services/coutellerie-laravel || exit 1

# Test PHP extensions immediately after installation
echo "🔍 Testing PHP extensions after installation..."
php -r "echo 'PDO MySQL: ' . (extension_loaded('pdo_mysql') ? '✅ OK' : '❌ MISSING') . PHP_EOL;"
php -r "echo 'MySQL: ' . (extension_loaded('mysql') ? '✅ OK' : '❌ MISSING') . PHP_EOL;"
php -r "echo 'MySQLi: ' . (extension_loaded('mysqli') ? '✅ OK' : '❌ MISSING') . PHP_EOL;"

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-intl --ignore-platform-req=ext-zip --ignore-platform-req=ext-pdo_mysql || exit 1

# Create .env from .env.production
echo "📄 Creating .env file..."
if [ -f .env.production ]; then
    cp .env.production .env || exit 1
    echo "✅ .env file created from .env.production"
else
    echo "⚠️ .env.production not found, checking if .env already exists..."
    if [ ! -f .env ]; then
        echo "❌ No .env or .env.production found, creating minimal .env..."
        cat > .env << EOF
APP_NAME="Coutellerie Svelte Laravel"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=

DB_CONNECTION=mysql
DB_HOST=\${MYSQL_HOST}
DB_PORT=\${MYSQL_PORT}
DB_DATABASE=\${MYSQL_DATABASE}
DB_USERNAME=\${MYSQL_USER}
DB_PASSWORD=\${MYSQL_PASSWORD}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_CHANNEL=stderr
EOF
    fi
fi

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    # Generate a new key and export it
    APP_KEY=$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")
    export APP_KEY
    echo "APP_KEY=$APP_KEY" >> .env
    echo "✅ Application key generated and set"
else
    echo "✅ Using existing APP_KEY from environment"
    echo "APP_KEY=$APP_KEY" >> .env
fi

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force || {
    echo "⚠️ Migrations failed, continuing..."
}

# Test database connection
echo "🔗 Testing database connection..."
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connection: OK';" || echo "⚠️ Database connection failed, but continuing..."

# Cache configurations for better performance
echo "⚡ Caching configurations..."
php artisan config:cache || echo "⚠️ Config cache failed, continuing..."

# Start the Laravel server
echo "🌟 Starting Laravel server on port $PORT..."
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"