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