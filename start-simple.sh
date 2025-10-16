#!/bin/bash

# Script de démarrage simplifié pour Railway Laravel
echo "🚀 Railway Laravel - Démarrage..."

# Navigation vers le répertoire Laravel
cd services/coutellerie-laravel || exit 1

# Installation des dépendances
echo "📦 Installation Composer..."
composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Configuration environnement
echo "⚙️ Configuration Laravel..."
if [ ! -f .env ] && [ -f .env.production ]; then
    cp .env.production .env
fi

# Génération clé si nécessaire  
php artisan key:generate --force 2>/dev/null || true

# Migrations base de données
echo "🗄️ Migrations..."
php artisan migrate --force 2>/dev/null || echo "⚠️ Migrations ignorées"

# Cache Laravel
php artisan config:cache 2>/dev/null || true

# Démarrage serveur
echo "🌟 Serveur Laravel sur port $PORT..."
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"