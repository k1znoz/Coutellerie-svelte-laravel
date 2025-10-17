#!/bin/bash

# Railway deployment script for Laravel - Simple approach
echo "🚀 Railway Laravel deployment..."

# Use apt packages instead of compiling (more reliable on Railway)
echo "📦 Installing PHP MySQL extensions via apt..."
export DEBIAN_FRONTEND=noninteractive
apt-get update -qq > /dev/null 2>&1

# Install PHP extensions via system packages
apt-get install -y -qq \
    php8.3-mysql \
    php8.3-pdo-mysql \
    php8.3-mysqli \
    php8.3-intl \
    php8.3-zip \
    default-mysql-client > /dev/null 2>&1

# Create PHP configuration to ensure extensions are loaded  
mkdir -p /usr/local/etc/php/conf.d/
cat > /usr/local/etc/php/conf.d/99-mysql.ini << 'EOF'
extension=pdo_mysql
extension=mysqli
extension=intl
extension=zip
EOF

# Change to Laravel directory
cd services/coutellerie-laravel || exit 1

# Quick extension check
echo "🔍 Quick extension check..."
php -r "echo 'PDO MySQL: ' . (extension_loaded('pdo_mysql') ? '✅' : '❌') . PHP_EOL;"

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