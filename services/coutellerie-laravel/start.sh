#!/bin/bash

# Script de démarrage pour Railway
echo "🚀 Démarrage Laravel..."

# Générer la clé si nécessaire
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Migrations et optimisations
php artisan migrate --force
php artisan storage:link --force 2>/dev/null || true

# Assets Filament
php artisan filament:assets

# Cache optimizations
php artisan config:cache
php artisan route:cache

echo "✅ Application prête !"