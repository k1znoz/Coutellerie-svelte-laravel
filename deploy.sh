#!/bin/bash

echo "🚀 Déploiement Railway..."

# Aller dans le dossier Laravel
cd services/coutellerie-laravel

# Installer les dépendances
composer install --no-dev --optimize-autoloader

# Migrer la base de données
php artisan migrate --force

# Démarrer le serveur
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}