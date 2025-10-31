#!/bin/bash
# Script d'initialisation pour le déploiement Railway - Service Principal
# Make sure this file has executable permissions, run `chmod +x railway/init-app.sh`

# Exit the script if any command fails
set -e

echo "🚀 Initialisation du service principal Laravel sur Railway..."

# Vérification des variables d'environnement critiques
if [ -z "$APP_KEY" ]; then
    echo "❌ APP_KEY manquante - génération automatique..."
    php artisan key:generate --force
fi

# Vérification de la base de données
if [ -z "$DATABASE_URL" ] && [ -z "$DB_HOST" ]; then
    echo "❌ Configuration base de données manquante"
    exit 1
fi

echo "✅ Variables d'environnement validées"

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