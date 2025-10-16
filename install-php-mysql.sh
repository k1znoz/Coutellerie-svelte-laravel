#!/bin/bash
set -e

echo "🐘 Installing PHP MySQL extensions for Railway..."

# Install required PHP extensions
if command -v apt-get >/dev/null 2>&1; then
    echo "📦 Installing via apt-get..."
    apt-get update
    # Try multiple package name variations
    apt-get install -y php8.3-mysql php8.3-pdo || \
    apt-get install -y php8.2-mysql php8.2-pdo || \
    apt-get install -y php8.1-mysql php8.1-pdo || \
    apt-get install -y php-mysqli php-pdo || \
    apt-get install -y php8.3-mysqli || \
    apt-get install -y php8.2-mysqli || \
    echo "Could not install via apt-get"
elif command -v apk >/dev/null 2>&1; then
    echo "📦 Installing via apk (Alpine)..."
    apk add --no-cache php81-mysqli php81-pdo_mysql
elif command -v yum >/dev/null 2>&1; then
    echo "📦 Installing via yum..."
    yum install -y php-mysql php-pdo
else
    echo "⚠️ Package manager not found, trying docker-php-ext-install..."
    if command -v docker-php-ext-install >/dev/null 2>&1; then
        echo "📦 Installing via docker-php-ext-install..."
        docker-php-ext-install mysqli pdo_mysql
    else
        echo "⚠️ No docker-php-ext-install, checking if MySQL extensions are already available..."
        
        # Check if extensions are already loaded
        if php -m | grep -i pdo_mysql >/dev/null 2>&1; then
            echo "✅ pdo_mysql extension already available!"
        elif php -m | grep -i mysqli >/dev/null 2>&1; then
            echo "✅ mysqli extension already available!"
        else
            echo "❌ Could not install or find MySQL PHP extensions"
            echo "Available PHP extensions:"
            php -m
            echo "PHP version and info:"
            php --version
            
            # Try to continue anyway - sometimes extensions are dynamically loaded
            echo "⚠️ Continuing anyway - extensions might be dynamically loaded"
        fi
    fi
fi

echo "✅ MySQL PHP extensions installation completed"
echo "Available PHP extensions:"
php -m | grep -i mysql || echo "No MySQL extensions found"