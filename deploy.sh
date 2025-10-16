#!/bin/bash

# Railway deployment script for Laravel
echo "🚀 Starting Railway deployment..."

# Install PHP extensions using Docker PHP extension installer
echo "🔧 Installing PHP extensions via docker-php-ext-install..."
export DEBIAN_FRONTEND=noninteractive

# First update package list and install required system packages
echo "📦 Installing system dependencies..."
apt-get update -qq
apt-get install -y -qq libicu-dev libzip-dev libxml2-dev libcurl4-openssl-dev libpng-dev libjpeg-dev libfreetype6-dev default-mysql-client libonig-dev gettext-base 2>/dev/null || echo "⚠️ Some system packages may already be installed"

# Clean any existing extension compilation artifacts
rm -rf /usr/src/php/ext/*/tmp-php* 2>/dev/null || true
rm -rf /usr/src/php/ext/*/.libs 2>/dev/null || true

echo "🔧 Installing core PHP extensions..."
# Install extensions one by one for better error handling
docker-php-ext-install pdo 2>/dev/null || echo "⚠️ PDO installation had warnings (may already exist)"
docker-php-ext-install pdo_mysql 2>/dev/null || echo "⚠️ PDO MySQL installation had warnings (may already exist)"
docker-php-ext-install mysqli 2>/dev/null || echo "⚠️ MySQLi installation had warnings (may already exist)"
docker-php-ext-install intl 2>/dev/null || echo "⚠️ Intl installation had warnings (may already exist)"
docker-php-ext-install zip 2>/dev/null || echo "⚠️ Zip installation had warnings (may already exist)"
docker-php-ext-install xml 2>/dev/null || echo "⚠️ XML installation had warnings (may already exist)"
docker-php-ext-install mbstring 2>/dev/null || echo "⚠️ mbstring installation had warnings (may already exist)"

echo "🎨 Configuring and installing GD extension..."
docker-php-ext-configure gd --with-freetype --with-jpeg 2>/dev/null || echo "⚠️ GD configure had warnings"
docker-php-ext-install gd 2>/dev/null || echo "⚠️ GD installation had warnings (may already exist)"

echo "✅ PHP extension installation completed"

# Change to Laravel directory
cd services/coutellerie-laravel || exit 1

# Test PHP extensions immediately after installation
echo "🔍 Testing PHP extensions after installation..."
php -r "echo 'PDO: ' . (extension_loaded('pdo') ? '✅ OK' : '❌ MISSING') . PHP_EOL;"
php -r "echo 'PDO MySQL: ' . (extension_loaded('pdo_mysql') ? '✅ OK' : '❌ MISSING') . PHP_EOL;"
php -r "echo 'MySQLi: ' . (extension_loaded('mysqli') ? '✅ OK' : '❌ MISSING') . PHP_EOL;"
php -r "echo 'Intl: ' . (extension_loaded('intl') ? '✅ OK' : '❌ MISSING') . PHP_EOL;"
php -r "echo 'Zip: ' . (extension_loaded('zip') ? '✅ OK' : '❌ MISSING') . PHP_EOL;"
echo "📋 All loaded extensions:"
php -m | grep -E "(pdo|mysql|intl|zip|xml|gd|mbstring)" | head -10

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-intl --ignore-platform-req=ext-zip --ignore-platform-req=ext-pdo_mysql || exit 1

# Create .env from .env.production with environment variable substitution
echo "📄 Creating .env file with environment variables..."
if [ -f .env.production ]; then
    # Use envsubst to substitute environment variables, but preserve Laravel's ${} syntax for non-Railway vars
    envsubst '$MYSQL_HOST $MYSQL_PORT $MYSQL_DATABASE $MYSQL_USER $MYSQL_PASSWORD $DATABASE_URL' < .env.production > .env || exit 1
    echo "✅ .env file created from .env.production with variable substitution"
else
    echo "⚠️ .env.production not found, creating .env with Railway MySQL variables..."
    cat > .env << EOF
APP_NAME="Coutellerie Svelte Laravel"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=

DB_CONNECTION=mysql
DB_HOST=${MYSQL_HOST}
DB_PORT=${MYSQL_PORT}
DB_DATABASE=${MYSQL_DATABASE}
DB_USERNAME=${MYSQL_USER}
DB_PASSWORD=${MYSQL_PASSWORD}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_CHANNEL=stderr
LOG_LEVEL=info
EOF
fi

# Show database configuration for debugging (without passwords)
echo "🔍 Database configuration:"
echo "DB_HOST: ${MYSQL_HOST:-'NOT_SET'}"
echo "DB_PORT: ${MYSQL_PORT:-'NOT_SET'}"  
echo "DB_DATABASE: ${MYSQL_DATABASE:-'NOT_SET'}"
echo "DB_USERNAME: ${MYSQL_USER:-'NOT_SET'}"
echo "DATABASE_URL: ${DATABASE_URL:0:20}..." # Show first 20 chars only

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