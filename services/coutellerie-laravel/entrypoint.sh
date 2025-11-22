#!/bin/sh
set -e

# Créer le lien symbolique pour que les images soient accessibles publiquement
php artisan storage:link

# Lancer les migrations de la base de données (sans supprimer les données)
php artisan migrate --force

# Lancer les seeders (qui inclut la création de l'admin)
php artisan db:seed --force

# Démarrer le serveur Apache en avant-plan
exec apache2-foreground