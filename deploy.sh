#!/bin/bash

# Railway deployment script for Laravel
echo "🚀 Railway Laravel deployment..."

# Install PHP extensions silently
echo "🔧 Installing PHP extensions..."
export DEBIAN_FRONTEND=noninteractive
apt-get update -qq > /dev/null 2>&1
apt-get install -y -qq libicu-dev libzip-dev default-mysql-client > /dev/null 2>&1

# Install required PHP extensions
docker-php-ext-install pdo_mysql intl zip > /dev/null 2>&1 || echo "⚠️ Some extensions may already exist"

# Change to Laravel directory
cd services/coutellerie-laravel || exit 1

# Install dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader --quiet || exit 1

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

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force || echo "⚠️ Migrations failed"

# Cache config for production
php artisan config:cache > /dev/null 2>&1

# Start server
echo "🌟 Starting server on port ${PORT:-8000}..."
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"