#!/bin/bash

# Script de déploiement Laravel avec extensions PHP

echo "🚀 Déploiement Laravel Coutellerie"

#!/bin/bash

# Script de lancement Laravel API + Filament pour Railway

echo "🚀 Lancement Laravel API Coutellerie sur Railway"

# Vérification que l'APP_KEY est configurée
if [ -z "$APP_KEY" ]; then
    echo "❌ APP_KEY manquante - vérifiez les variables Railway"
    exit 1
fi

# Installation des dépendances Composer avec extensions
echo "📦 Installation des dépendances Composer..."
if ! composer install --optimize-autoloader --no-dev --no-scripts --no-interaction; then
    echo "⚠️  Installation avec --ignore-platform-reqs (extensions en cours d'activation)"
    composer install --optimize-autoloader --no-dev --no-scripts --no-interaction --ignore-platform-reqs
fi

# Vérification de la base de données
echo "🗃️ Configuration de la base de données..."
if [ -z "$DATABASE_URL" ] && [ -z "$DB_HOST" ]; then
    echo "❌ Configuration base de données manquante"
    exit 1
fi

echo "✅ Variables d'environnement configurées"

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
echo "🚀 Démarrage du serveur Laravel..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
# Extensions requises pour Laravel + Filament
REQUIRED_EXTENSIONS=("gd" "zip" "intl" "pdo_mysql" "bcmath" "mbstring" "xml" "curl")

for ext in "${REQUIRED_EXTENSIONS[@]}"; do
    if php -m | grep -q "^$ext$"; then
        echo "✅ Extension $ext : installée"
    else
        echo "❌ Extension $ext : manquante"
        
        # Installation selon le système
        if command -v apt-get &> /dev/null; then
            # Debian/Ubuntu
            case $ext in
                "gd") sudo apt-get install -y php-gd ;;
                "zip") sudo apt-get install -y php-zip ;;
                "intl") sudo apt-get install -y php-intl ;;
                "pdo_mysql") sudo apt-get install -y php-mysql ;;
                "bcmath") sudo apt-get install -y php-bcmath ;;
                "mbstring") sudo apt-get install -y php-mbstring ;;
                "xml") sudo apt-get install -y php-xml ;;
                "curl") sudo apt-get install -y php-curl ;;
            esac
        elif command -v yum &> /dev/null; then
            # CentOS/RHEL
            case $ext in
                "gd") sudo yum install -y php-gd ;;
                "zip") sudo yum install -y php-zip ;;
                "intl") sudo yum install -y php-intl ;;
                "pdo_mysql") sudo yum install -y php-mysqlnd ;;
                "bcmath") sudo yum install -y php-bcmath ;;
                "mbstring") sudo yum install -y php-mbstring ;;
                "xml") sudo yum install -y php-xml ;;
                "curl") sudo yum install -y php-curl ;;
            esac
        fi
    fi
done

# Vérification des dépendances Composer
echo "📦 Vérification de Composer..."
if ! command -v composer &> /dev/null; then
    echo "❌ Composer non trouvé, installation..."
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    echo "✅ Composer installé"
else
    echo "✅ Composer déjà installé"
fi

# Installation des dépendances
echo "📚 Installation des dépendances..."
composer install --optimize-autoloader --no-dev

# Configuration Laravel
echo "⚙️ Configuration Laravel..."

# Migration de la base de données
echo "🗃️ Migration de la base de données..."
php artisan migrate --force

# Génération des assets Filament
echo "🎨 Génération des assets Filament..."
php artisan filament:assets

# Optimisation du cache
echo "🚀 Optimisation du cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Création du lien de stockage
echo "🔗 Création du lien de stockage..."
php artisan storage:link

echo "✅ Déploiement terminé avec succès !"
echo "🌐 Application prête à fonctionner"
