#!/bin/bash
# Script de build pour Railway - Installation des extensions PHP

echo "🔧 Installation des extensions PHP..."

# Mise à jour des packages
apt-get update

# Installation des extensions PHP nécessaires
apt-get install -y \
    php8.3-intl \
    php8.3-zip \
    php8.3-xml \
    php8.3-curl \
    php8.3-mbstring \
    php8.3-gd \
    php8.3-mysql

echo "✅ Extensions PHP installées"
echo "🔍 Vérification des extensions:"
php -m | grep -E "(intl|zip|xml|curl|mbstring|gd|mysql)"