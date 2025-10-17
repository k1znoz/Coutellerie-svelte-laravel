#!/bin/bash

# Script de configuration des variables d'environnement pour Railway
echo "🔧 Configuration des variables d'environnement Railway..."

# Variables d'application Laravel
echo "APP_ENV=production"
echo "APP_DEBUG=false"
echo "APP_KEY=${APP_KEY}"
echo "APP_URL=${APP_URL}"

# Variables MySQL (automatiquement fournies par Railway)
echo "DB_CONNECTION=mysql"
echo "DB_HOST=\${{MYSQLHOST}}"
echo "DB_PORT=\${{MYSQLPORT}}"
echo "DB_DATABASE=\${{MYSQLDATABASE}}"
echo "DB_USERNAME=\${{MYSQLUSER}}"
echo "DB_PASSWORD=\${{MYSQLPASSWORD}}"

# Variables de cache et session
echo "CACHE_DRIVER=database"
echo "SESSION_DRIVER=database"
echo "QUEUE_CONNECTION=database"

echo ""
echo "📋 Copiez ces variables dans votre dashboard Railway :"
echo "Variables > Raw Editor"