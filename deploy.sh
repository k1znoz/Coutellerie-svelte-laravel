#!/bin/bash

# Railway deployment script for Laravel - Simple approach
echo "🚀 Railway Laravel deployment..."

# Install PHP extensions using docker-php-ext-install (Railway compatible)
echo "� Installing PHP extensions for Railway..."
export DEBIAN_FRONTEND=noninteractive
apt-get update -qq > /dev/null 2>&1

# Install required development libraries and Node.js
apt-get install -y -qq \
    libicu-dev \
    libzip-dev \
    default-mysql-client \
    libmysqlclient-dev \
    curl \
    wget \
    gnupg > /dev/null 2>&1

# Install Node.js 18 LTS for Railpack environment
echo "📦 Installing Node.js for Railway/Railpack..."
# Method 1: Try NodeSource repository
curl -fsSL https://deb.nodesource.com/setup_18.x | bash - > /dev/null 2>&1 && \
apt-get install -y nodejs > /dev/null 2>&1

# Method 2: Fallback - download binary directly if repository method fails
if ! command -v node >/dev/null 2>&1; then
    echo "🔄 Trying direct Node.js installation..."
    cd /tmp
    wget -q https://nodejs.org/dist/v18.18.2/node-v18.18.2-linux-x64.tar.xz
    tar -xf node-v18.18.2-linux-x64.tar.xz
    cp -r node-v18.18.2-linux-x64/* /usr/local/
    cd /app/services/coutellerie-laravel
fi

# Verify Node.js installation
echo "🔍 Checking Node.js installation..."
if command -v node >/dev/null 2>&1; then
    echo "Node.js version: $(node --version) ✅"
    echo "npm version: $(npm --version) ✅"
else
    echo "❌ Node.js installation failed"
fi

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

# Install Node.js dependencies and build assets
echo "📦 Installing Node.js dependencies..."
if command -v npm >/dev/null 2>&1; then
    echo "npm available, proceeding with installation..."
    npm ci --production=false --silent || {
        echo "⚠️ npm ci failed, trying npm install..."
        npm install --silent || echo "❌ npm install failed"
    }
    
    echo "🏗️ Building frontend assets..."
    if npm run build; then
        echo "✅ Asset build successful"
        # Verify build output
        if [ -d "public/build" ]; then
            echo "✅ Vite build directory created"
            ls -la public/build/ | head -5
        fi
    else
        echo "⚠️ Asset build failed - trying without cache"
        rm -rf node_modules/.cache 2>/dev/null
        npm run build || echo "❌ Asset build failed completely"
    fi
else
    echo "❌ npm not available, skipping asset build"
    echo "🔧 Creating empty build directory for compatibility..."
    mkdir -p public/build
    echo "/* Fallback CSS */" > public/build/app.css
fi

# Run package discovery
echo "🔍 Running package discovery..."
php artisan package:discover --ansi || echo "⚠️ Package discovery failed"
php artisan config:cache --quiet || echo "⚠️ Config cache failed"

echo "🚀 Bootstrapping Laravel application..."
php artisan about --only=environment || echo "⚠️ Laravel bootstrap check failed"



# Test database connection before migrations
echo "🔗 Testing database connection..."
if php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database: ✅';" 2>/dev/null; then
    echo "🗄️ Running migrations..."
    php artisan migrate --force || echo "⚠️ Migrations failed"
    
    echo "🎨 Setting up Filament..."
    php artisan filament:install --panels --force --quiet || echo "⚠️ Filament install failed"
    
    echo "🔧 Publishing and optimizing Filament assets..."
    php artisan vendor:publish --tag=filament-assets --force || echo "⚠️ Publishing assets failed"
    php artisan filament:assets --quiet || echo "⚠️ Filament assets failed"
    
    echo "🧹 Clearing all caches..."
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
    
    echo "🎨 Checking compiled assets..."
    if [ -d "public/build" ]; then
        echo "Vite build directory: ✅"
        ls -la public/build/ || echo "Build directory empty"
    else
        echo "Vite build directory: ❌"
    fi
    
    if [ -d "public/vendor/filament" ]; then
        echo "Filament assets: ✅"
    else
        echo "Filament assets: ❌"
    fi
else
    echo "❌ Database connection failed, skipping migrations"
fi

# Cache config
echo "⚡ Optimizing Laravel for production..."
php artisan config:cache > /dev/null 2>&1
php artisan route:cache > /dev/null 2>&1 || echo "⚠️ Route cache failed"
php artisan view:cache > /dev/null 2>&1 || echo "⚠️ View cache failed"

# Start server
echo "🌟 Starting server on port ${PORT:-8000}..."
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"