#!/bin/bash
# deploy.sh - Script de déploiement monorepo
# Variables d'environnement
FRONTEND_DIR="apps/coutellerie-svelte"
BACKEND_DIR="services/coutellerie-laravel"
SERVER="user@serveur.com"
REMOTE_FRONTEND="/var/www/frontend"
REMOTE_BACKEND="/var/www/backend"
BACKUP_DIR="/var/backups/$(date +%Y%m%d_%H%M%S)"

echo "🚀 Début du déploiement monorepo..."

# Vérification des prérequis
check_prerequisites() {
    echo "🔍 Vérification des prérequis..."
    
    # Vérifier Node.js et npm
    if ! command -v npm &> /dev/null; then
        echo "❌ npm non trouvé"
        exit 1
    fi
    
    # Vérifier Composer
    if ! command -v composer &> /dev/null; then
        echo "❌ Composer non trouvé"
        exit 1
    fi
    
    echo "✅ Prérequis validés"
}

# Build du frontend SvelteKit
build_frontend() {
    echo "🏗️ Build du frontend SvelteKit..."
    cd $FRONTEND_DIR
    
    # Installation des dépendances
    npm ci --production=false
    if [ $? -ne 0 ]; then
        echo "❌ Erreur installation npm"
        exit 1
    fi
    
    # Build optimisé
    npm run build
    if [ $? -ne 0 ]; then
        echo "❌ Erreur lors du build SvelteKit"
        exit 1
    fi
    
    echo "✅ Build SvelteKit terminé"
    cd ../..
}

# Préparation du backend Laravel
prepare_backend() {
    echo "📦 Préparation du backend Laravel..."
    cd $BACKEND_DIR
    
    # Installation des dépendances production
    composer install --no-dev --optimize-autoloader
    if [ $? -ne 0 ]; then
        echo "❌ Erreur installation Composer"
        exit 1
    fi
    
    # Optimisations Laravel
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    echo "✅ Backend Laravel préparé"
    cd ../..
}

# Sauvegarde et déploiement
deploy_applications() {
    echo "📡 Déploiement vers le serveur..."
    
    # Création de sauvegarde
    ssh $SERVER "mkdir -p $BACKUP_DIR"
    ssh $SERVER "cp -r $REMOTE_FRONTEND $BACKUP_DIR/frontend_backup"
    ssh $SERVER "cp -r $REMOTE_BACKEND $BACKUP_DIR/backend_backup"
    
    # Upload frontend SvelteKit
    echo "📤 Upload frontend..."
    rsync -avz --delete --exclude=node_modules \
        $FRONTEND_DIR/build/ $SERVER:$REMOTE_FRONTEND/
    rsync -avz $FRONTEND_DIR/static/ $SERVER:$REMOTE_FRONTEND/static/
    
    # Upload backend Laravel
    echo "📤 Upload backend..."
    rsync -avz --delete \
        --exclude=node_modules \
        --exclude=.env \
        --exclude=storage/logs \
        --exclude=storage/framework/cache \
        $BACKEND_DIR/ $SERVER:$REMOTE_BACKEND/
    
    # Configuration post-déploiement Laravel
    ssh $SERVER "cd $REMOTE_BACKEND && php artisan migrate --force"
    ssh $SERVER "cd $REMOTE_BACKEND && php artisan storage:link"
    ssh $SERVER "chmod -R 755 $REMOTE_BACKEND/storage"
    ssh $SERVER "chmod -R 755 $REMOTE_BACKEND/bootstrap/cache"
}

# Vérification post-déploiement
verify_deployment() {
    echo "🔍 Vérification du déploiement..."
    
    # Test frontend
    FRONTEND_STATUS=$(curl -s -o /dev/null -w "%{http_code}" https://coutellerie-artisanale.com)
    if [ "$FRONTEND_STATUS" != "200" ]; then
        echo "❌ Frontend inaccessible (HTTP $FRONTEND_STATUS)"
        rollback_deployment
        exit 1
    fi
    
    # Test API Laravel
    API_STATUS=$(curl -s -o /dev/null -w "%{http_code}" https://coutellerie-artisanale.com/api/health)
    if [ "$API_STATUS" != "200" ]; then
        echo "❌ API inaccessible (HTTP $API_STATUS)"
        rollback_deployment
        exit 1
    fi
    
    echo "✅ Déploiement vérifié avec succès"
}

# Rollback en cas d'erreur
rollback_deployment() {
    echo "🔄 Rollback en cours..."
    ssh $SERVER "cp -r $BACKUP_DIR/frontend_backup/* $REMOTE_FRONTEND/"
    ssh $SERVER "cp -r $BACKUP_DIR/backend_backup/* $REMOTE_BACKEND/"
    ssh $SERVER "cd $REMOTE_BACKEND && php artisan config:cache"
    echo "✅ Rollback terminé"
}

# Nettoyage
cleanup() {
    echo "🧹 Nettoyage..."
    ssh $SERVER "find /var/backups -type d -mtime +7 -exec rm -rf {} \;"
    echo "✅ Nettoyage terminé"
}

# Exécution du script
main() {
    check_prerequisites
    build_frontend
    prepare_backend
    deploy_applications
    verify_deployment
    cleanup
    echo "🎉 Déploiement monorepo terminé avec succès !"
}

# Gestion des erreurs
set -e
trap 'echo "❌ Erreur détectée, rollback automatique" && rollback_deployment' ERR

# Lancement
main