#!/bin/bash
# Script d'initialisation Laravel pour NIXPACKS sur Railway

set -e

echo "🚀 Initialisation Laravel avec NIXPACKS..."

# Vérification APP_KEY
if [ -z "$APP_KEY" ]; then
    echo "❌ APP_KEY manquante - génération..."
    php artisan key:generate --force --show
fi

echo "✅ Configuration validée"

# Run migrations
echo "🔄 Exécution des migrations..."
php artisan migrate --force

# Clear all caches before optimization
echo "🧹 Nettoyage des caches..."
php artisan optimize:clear

# Cache the various components of the Laravel application
echo "⚡ Optimisation pour la production..."
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# Génération des assets Filament (interface admin)
echo "🎨 Génération des assets Filament..."
php artisan filament:assets

# Création du lien de stockage pour les uploads
echo "🔗 Configuration du stockage..."
php artisan storage:link

# Configuration des permissions
echo "🔐 Configuration des permissions..."
chmod -R 755 storage bootstrap/cache

# Vérification que tout est prêt
echo "🔍 Vérification finale..."
php artisan about --only=environment

echo "✅ Service principal Laravel prêt ! 🎉"
echo "🌐 API disponible pour le frontend SvelteKit"