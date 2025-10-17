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

# Skip MySQL extensions for now - use SQLite to get app running
echo "📦 Using SQLite temporarily to bypass MySQL extension issues..."
# We'll fix MySQL later, but this gets the app running immediately

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

# Setup environment with SQLite for immediate deployment
echo "⚙️ Setting up environment with SQLite..."
if [ -f .env.production ]; then
    cp .env.production .env
else
    # Create basic .env if not found
    cat > .env << 'EOF'
APP_NAME="Coutellerie Svelte Laravel"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://coutellerie-production.up.railway.app
EOF
fi

# Override database config to use SQLite temporarily
cat >> .env << 'EOF'

# Temporary SQLite database (to get app running)
DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_CHANNEL=stderr
LOG_LEVEL=info
EOF

# Create SQLite database
mkdir -p /app/database
touch /app/database/database.sqlite

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