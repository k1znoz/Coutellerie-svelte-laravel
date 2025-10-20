#!/bin/bash

# Script de démarrage pour Railway/Railpack
echo "🚀 Démarrage Laravel pour production..."

# Exécuter les migrations
php artisan migrate --force

# Créer le lien de stockage
php artisan storage:link

# Optimiser pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Application prête pour production !"