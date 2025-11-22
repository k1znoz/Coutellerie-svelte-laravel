#!/bin/sh
set -e

# Créer le lien symbolique pour que les images soient accessibles publiquement
php artisan storage:link

# --- LIGNE TEMPORAIRE : RÉINITIALISER COMPLÈTEMENT LA BASE DE DONNÉES ---
# Cette commande va SUPPRIMER toutes les tables et les recréer avec la nouvelle structure
# ATTENTION : Toutes les données seront perdues !
php artisan migrate:fresh --force

# Lancer les seeders (qui inclut la création de l'admin)
php artisan db:seed --force

# Démarrer le serveur Apache en avant-plan
exec apache2-foreground