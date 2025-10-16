#!/bin/bash

# Script de démarrage simplifié pour Railway
echo "🚀 Démarrage de l'application Coutellerie..."

# Variables d'environnement
FRONTEND_DIR="apps/coutellerie-svelte"
BACKEND_DIR="services/coutellerie-laravel"

# Fonction de vérification des prérequis
check_prerequisites() {
    echo "🔍 Vérification des prérequis..."
    
    # Vérifier Node.js et npm
    if ! command -v npm >/dev/null 2>&1; then
        echo "❌ npm non trouvé"
        return 1
    fi
    
    # Vérifier Composer
    if ! command -v composer >/dev/null 2>&1; then
        echo "❌ Composer non trouvé"
        return 1
    fi
    
    echo "✅ Prérequis validés"
    return 0
}

# Build du frontend SvelteKit
build_frontend() {
    echo "🏗️ Build du frontend SvelteKit..."
    cd "$FRONTEND_DIR" || return 1
    
    # Installation des dépendances
    echo "📦 Installation des dépendances npm..."
    npm ci --production=false || {
        echo "❌ Erreur installation npm"
        return 1
    }
    
    # Build optimisé
    echo "🔨 Construction du build SvelteKit..."
    npm run build || {
        echo "❌ Erreur lors du build SvelteKit"
        return 1
    }
    
    echo "✅ Build SvelteKit terminé"
    cd - >/dev/null || return 1
    return 0
}

# Préparation du backend Laravel
prepare_backend() {
    echo "📦 Préparation du backend Laravel..."
    cd "$BACKEND_DIR" || return 1
    
    # Installation des dépendances production
    echo "📥 Installation des dépendances Composer..."
    composer install --no-dev --optimize-autoloader --ignore-platform-reqs || {
        echo "❌ Erreur installation Composer"
        return 1
    }
    
    # Vérifier si .env existe, sinon créer depuis .env.production
    if [ ! -f .env ] && [ -f .env.production ]; then
        echo "📄 Copie de .env.production vers .env..."
        cp .env.production .env
    fi
    
    # Générer la clé d'application si nécessaire
    if ! php artisan config:show app.key >/dev/null 2>&1; then
        echo "🔑 Génération de la clé d'application..."
        php artisan key:generate --force
    fi
    
    # Migrations de base de données
    echo "🗄️ Exécution des migrations..."
    php artisan migrate --force || echo "⚠️ Migrations échouées, continuons..."
    
    # Optimisations Laravel
    echo "⚡ Optimisations Laravel..."
    php artisan config:cache || echo "⚠️ Config cache échoué"
    php artisan route:cache || echo "⚠️ Route cache échoué"
    php artisan view:cache || echo "⚠️ View cache échoué"
    
    echo "✅ Backend Laravel préparé"
    cd - >/dev/null || return 1
    return 0
}

# Démarrage de l'application
start_application() {
    echo "� Démarrage du serveur Laravel..."
    cd "$BACKEND_DIR" || return 1
    
    # Vérification de l'environnement
    echo "🌍 Environnement: $(php artisan env 2>/dev/null || echo 'inconnu')"
    
    # Démarrage du serveur Laravel
    echo "🌟 Serveur Laravel en cours de démarrage sur le port $PORT..."
    php artisan serve --host=0.0.0.0 --port="${PORT:-8000}" || {
        echo "❌ Impossible de démarrer le serveur Laravel"
        return 1
    }
}

# Fonction de nettoyage (optionnelle)
cleanup() {
    echo "🧹 Nettoyage des fichiers temporaires..."
    # Nettoyer les caches Laravel si nécessaire
    cd "$BACKEND_DIR" 2>/dev/null && {
        php artisan cache:clear 2>/dev/null || true
        php artisan config:clear 2>/dev/null || true
    }
    echo "✅ Nettoyage terminé"
}

# Fonction principale
main() {
    echo "🎯 Démarrage de l'application Coutellerie Laravel..."
    
    # Vérification des prérequis
    if ! check_prerequisites; then
        echo "❌ Échec de la vérification des prérequis"
        exit 1
    fi
    
    # Préparation du backend (priorité pour Railway)
    if ! prepare_backend; then
        echo "❌ Échec de la préparation du backend"
        exit 1
    fi
    
    # Démarrage de l'application
    if ! start_application; then
        echo "❌ Échec du démarrage de l'application"
        exit 1
    fi
    
    echo "🎉 Application démarrée avec succès !"
}

# Point d'entrée du script
main "$@"