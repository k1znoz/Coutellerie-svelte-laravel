#!/bin/bash

# Script de déploiement pour Railway - Coutellerie Svelte Laravel
echo "🚀 Démarrage du déploiement Railway..."

# Configuration des variables d'environnement Laravel
export APP_ENV=${APP_ENV:-production}
export APP_DEBUG=${APP_DEBUG:-false}
export APP_KEY=${APP_KEY}
export APP_URL=${APP_URL:-https://coutellerie-production.up.railway.app}

# Configuration MySQL avec les variables Railway
export DB_CONNECTION=mysql
export DB_HOST=${MYSQLHOST:-mysql.railway.internal}
export DB_PORT=${MYSQLPORT:-3306}
export DB_DATABASE=${MYSQLDATABASE:-railway}
export DB_USERNAME=${MYSQLUSER:-root}
export DB_PASSWORD=${MYSQLPASSWORD}

echo "📦 Installation des dépendances Laravel..."
cd services/coutellerie-laravel
composer install --no-dev --optimize-autoloader --no-interaction

echo "🔧 Configuration Laravel..."
# Génération de la clé APP_KEY si elle n'existe pas
if [ -z "$APP_KEY" ]; then
    echo "🔑 Génération d'une nouvelle clé d'application..."
    php artisan key:generate --force
fi

# Cache des configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🗄️  Migrations de la base de données..."
php artisan migrate --force

echo "📁 Optimisation des fichiers..."
php artisan storage:link

echo "🔧 Installation des dépendances Svelte..."
cd ../../apps/coutellerie-svelte
npm ci
npm run build

echo "🌐 Démarrage du serveur Laravel..."
cd ../../services/coutellerie-laravel
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}