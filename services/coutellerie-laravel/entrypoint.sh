#!/bin/sh
set -e

# Supprimer le lien symbolique s'il existe déjà (pour éviter les erreurs)
if [ -L /var/www/html/public/storage ]; then
    rm /var/www/html/public/storage
fi

# Créer le lien symbolique pour que les images soient accessibles publiquement
php artisan storage:link

# Lancer les migrations de la base de données (sans supprimer les données)
php artisan migrate --force

# Lancer les seeders (qui inclut la création de l'admin)
php artisan db:seed --force

# Démarrer le serveur Apache en avant-plan
exec apache2-foreground