#!/bin/bash
set -e

echo "🐘 Installing PHP MySQL extensions for Railway..."

# Install required PHP extensions
if command -v apt-get >/dev/null 2>&1; then
    echo "📦 Installing via apt-get..."
    apt-get update
    apt-get install -y php-mysql php-pdo-mysql
elif command -v apk >/dev/null 2>&1; then
    echo "📦 Installing via apk (Alpine)..."
    apk add --no-cache php81-mysqli php81-pdo_mysql
elif command -v yum >/dev/null 2>&1; then
    echo "📦 Installing via yum..."
    yum install -y php-mysql php-pdo
else
    echo "⚠️ Package manager not found, trying docker-php-ext-install..."
    if command -v docker-php-ext-install >/dev/null 2>&1; then
        docker-php-ext-install mysqli pdo_mysql
    else
        echo "❌ Could not install MySQL PHP extensions"
        echo "Available PHP extensions:"
        php -m | grep -i mysql || echo "No MySQL extensions found"
        exit 1
    fi
fi

echo "✅ MySQL PHP extensions installation completed"
echo "Available PHP extensions:"
php -m | grep -i mysql || echo "No MySQL extensions found"