#!/bin/bash
set -e

echo "🏗️ Building Svelte application..."

# Build Svelte frontend
if [ -d "apps/coutellerie-svelte" ]; then
    echo "📦 Building Svelte frontend..."
    cd apps/coutellerie-svelte
    npm ci
    npx svelte-kit sync
    npm run build
    cd ../..
    echo "✅ Svelte build complete!"
else
    echo "⚠️ Svelte directory not found, skipping..."
fi

# Install PHP dependencies if Composer is available
echo "🔍 Checking for PHP/Composer availability..."
if command -v composer >/dev/null 2>&1; then
    echo "📦 Installing PHP dependencies..."
    cd services/coutellerie-laravel
    composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs
    cd ../..
    echo "✅ PHP dependencies installed!"
else
    echo "⚠️ Composer not available in build environment, will install at runtime"
fi