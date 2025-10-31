#!/bin/bash

# Script de lancement du service principal Laravel sur Railway
# La configuration et initialisation sont gérées par railway/init-app.sh (preDeployCommand)

echo "🚀 Démarrage du service principal Laravel API + Filament"

# Vérification rapide des variables critiques
if [ -z "$APP_KEY" ]; then
    echo "❌ APP_KEY manquante - l'initialisation a échoué"
    exit 1
fi

echo "✅ Service principal prêt à démarrer"
echo "🌐 API Laravel + Filament Admin disponible"
echo "� Port: ${PORT:-8080}"

# Démarrage du serveur PHP-FPM + Nginx (géré automatiquement par Railway/Nixpacks)
# Ou utilisation d'artisan serve en fallback
if command -v nginx >/dev/null 2>&1; then
    echo "🚀 Démarrage avec PHP-FPM + Nginx (recommandé)"
    # Railway/Nixpacks gère automatiquement php-fpm et nginx
else
    echo "🚀 Démarrage avec Artisan Serve (fallback)"
    exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
fi
