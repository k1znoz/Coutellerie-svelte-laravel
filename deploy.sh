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
    echo "🔍 Before substitution - checking .env.production database section:"
    grep -E "^DB_" .env.production || echo "No DB_ variables found in .env.production"
    
    # Use envsubst to substitute only MYSQL_PUBLIC_URL (most reliable)
    echo "🔄 Running envsubst with MYSQL_PUBLIC_URL only..."
    envsubst '$MYSQL_PUBLIC_URL' < .env.production > .env || exit 1
    echo "✅ .env file created from .env.production with variable substitution"
else
    echo "⚠️ .env.production not found, creating .env with MYSQL_PUBLIC_URL..."
    cat > .env << EOF
APP_NAME="Coutellerie Svelte Laravel"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=

DB_URL=${MYSQL_PUBLIC_URL}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_CHANNEL=stderr
LOG_LEVEL=info
EOF
fi

# Debug: Show Railway environment info
echo "🔍 Railway environment:"
echo "MYSQL_PUBLIC_URL available: ${MYSQL_PUBLIC_URL:+YES}"
echo "MYSQL_URL available: ${MYSQL_URL:+YES}"

# Test different possible variable names
echo "🔍 Testing variable name variations:"
echo "MYSQLHOST: ${MYSQLHOST:-'NOT_SET'}"
echo "MYSQL_HOST: ${MYSQL_HOST:-'NOT_SET'}"
echo "MYSQLPORT: ${MYSQLPORT:-'NOT_SET'}"
echo "MYSQL_PORT: ${MYSQL_PORT:-'NOT_SET'}"
echo "MYSQLDATABASE: ${MYSQLDATABASE:-'NOT_SET'}"
echo "MYSQL_DATABASE: ${MYSQL_DATABASE:-'NOT_SET'}"
echo "MYSQLUSER: ${MYSQLUSER:-'NOT_SET'}"
echo "MYSQL_USER: ${MYSQL_USER:-'NOT_SET'}"
echo "RAILWAY_ENVIRONMENT: ${RAILWAY_ENVIRONMENT:-'NOT_SET'}"
echo "RAILWAY_PROJECT_NAME: ${RAILWAY_PROJECT_NAME:-'NOT_SET'}"
echo "RAILWAY_SERVICE_NAME: ${RAILWAY_SERVICE_NAME:-'NOT_SET'}"

# Check for private network variables
echo "🔍 ALL Railway MySQL variables found:"
env | grep -i mysql | sort

# Show database configuration for debugging
echo "🔍 Database configuration:"
echo "MYSQL_PUBLIC_URL: ${MYSQL_PUBLIC_URL:0:50}..." # Show first 50 chars only

# Debug: Show what's actually in the .env file after substitution
echo "🔍 Generated .env file (database section):"
grep -E "^(DB_|DATABASE_)" .env || echo "No database variables found in .env"

# Debug: Test Laravel database configuration parsing
echo "🔍 Laravel database configuration:"
php artisan tinker --execute="
echo 'Database config:' . PHP_EOL;
\$config = config('database.connections.mysql');
if (is_null(\$config)) {
    echo 'MySQL config is null, checking default connection...' . PHP_EOL;
    \$config = config('database.connections.' . config('database.default'));
}
if (\$config) {
    echo 'Host: ' . (\$config['host'] ?? 'NOT_SET') . PHP_EOL;
    echo 'Port: ' . (\$config['port'] ?? 'NOT_SET') . PHP_EOL;
    echo 'Database: ' . (\$config['database'] ?? 'NOT_SET') . PHP_EOL;
    echo 'Username: ' . (\$config['username'] ?? 'NOT_SET') . PHP_EOL;
} else {
    echo 'No database config found!' . PHP_EOL;
}
" || echo "⚠️ Failed to read Laravel database config"

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

# Test network connectivity to MySQL host first
echo "🌐 Testing network connectivity to MySQL..."
if [ -n "$MYSQL_PUBLIC_URL" ]; then
    echo "Using MYSQL_PUBLIC_URL: ${MYSQL_PUBLIC_URL:0:50}..."
    # Extract host and port from MYSQL_PUBLIC_URL
    MYSQL_HOST_EXTRACTED=$(echo "$MYSQL_PUBLIC_URL" | sed -n 's/.*@\([^:]*\):.*/\1/p')
    MYSQL_PORT_EXTRACTED=$(echo "$MYSQL_PUBLIC_URL" | sed -n 's/.*:\([0-9]*\)\/.*/\1/p')
    echo "Extracted host: $MYSQL_HOST_EXTRACTED, port: $MYSQL_PORT_EXTRACTED"
    
    # Test if we can reach the MySQL host
    timeout 10 bash -c "echo >/dev/tcp/$MYSQL_HOST_EXTRACTED/$MYSQL_PORT_EXTRACTED" && echo "✅ Network connectivity OK" || echo "❌ Cannot reach MySQL host"
    
    # Test with mysql client if available
    if command -v mysql >/dev/null 2>&1; then
        echo "🔧 Testing MySQL client connection with URL..."
        timeout 10 mysql "$MYSQL_PUBLIC_URL" --connect-timeout=5 --execute="SELECT 1;" && echo "✅ MySQL client connection OK" || echo "⚠️ MySQL client connection failed"
    fi
fi

# Test database connection with retry
echo "🔗 Testing database connection (with retry)..."
for i in {1..3}; do
    echo "Attempt $i/3..."
    php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connection: OK';" && break || {
        echo "⚠️ Database connection attempt $i failed"
        if [ $i -lt 3 ]; then
            echo "Waiting 5 seconds before retry..."
            sleep 5
        fi
    }
done

# Cache configurations for better performance
echo "⚡ Caching configurations..."
php artisan config:cache || echo "⚠️ Config cache failed, continuing..."

# Start the Laravel server
echo "🌟 Starting Laravel server on port $PORT..."
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"