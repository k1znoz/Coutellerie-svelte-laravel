#!/bin/bash
set -e

echo "🏗️ Building application..."

# Build Svelte frontend
if [ -d "apps/coutellerie-svelte" ]; then
    echo "📦 Building Svelte frontend..."
    cd apps/coutellerie-svelte
    npm ci
    npx svelte-kit sync
    npm run build
    cd ../..
fi

# Install Laravel dependencies
echo "🎵 Installing Laravel dependencies..."
cd services/coutellerie-laravel
composer install --no-dev --optimize-autoloader --no-interaction

# Copy Svelte build to Laravel public (if exists)
if [ -d "../../apps/coutellerie-svelte/build" ]; then
    echo "📋 Copying Svelte build to Laravel public..."
    mkdir -p public/app
    cp -r ../../apps/coutellerie-svelte/build/* public/app/ 2>/dev/null || true
fi

echo "✅ Build complete!"