#!/bin/bash

# Script de lancement Laravel API + Filament pour Railway

echo "🚀 Lancement Laravel API Coutellerie sur Railway"

# Vérification que l'APP_KEY est configurée
if [ -z "$APP_KEY" ]; then
    echo "❌ APP_KEY manquante - vérifiez les variables Railway"
    exit 1
fi

# Vérification de la base de données
echo "🗃️ Configuration de la base de données..."
if [ -z "$DATABASE_URL" ] && [ -z "$DB_HOST" ]; then
    echo "❌ Configuration base de données manquante"
    exit 1
fi

echo "✅ Variables d'environnement configurées"

# Vérification des extensions PHP critiques
echo "🔧 Vérification des extensions PHP..."
php -m | grep -E "(intl|zip)" || echo "⚠️ Extensions intl/zip: vérifiez la config RAILPACK"

# Migration de la base de données
echo "🔄 Migration de la base de données..."
php artisan migrate --force

# Génération des assets Filament (interface admin)
echo "🎨 Génération des assets Filament..."
php artisan filament:assets

# Optimisation pour la production
echo "⚡ Optimisation pour la production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Création du lien de stockage pour les uploads
echo "🔗 Configuration du stockage..."
php artisan storage:link

# Configuration des permissions
echo "🔐 Configuration des permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "✅ Laravel API prête !"
echo "🌐 API disponible pour le frontend SvelteKit"
echo "👤 Panel admin Filament disponible sur /admin"

# Lancement du serveur Laravel
echo "🚀 Démarrage du serveur Laravel sur le port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
