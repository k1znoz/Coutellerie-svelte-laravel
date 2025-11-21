#!/bin/sh
set -e

# Lancer les migrations de la base de données
php artisan migrate --force

# Lancer les seeders (qui inclut la création de l'admin)
php artisan db:seed --force

# Démarrer le serveur Apache en avant-plan
exec apache2-foreground
