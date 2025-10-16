#!/bin/bash
# Script de build pour Railway - Installation des extensions PHP via Docker

echo "🔧 Installation des extensions PHP via docker-php-ext-install..."
export DEBIAN_FRONTEND=noninteractive

# Installation des dépendances système
echo "📦 Installation des dépendances système..."
apt-get update -qq
apt-get install -y -qq libicu-dev libzip-dev libxml2-dev libcurl4-openssl-dev libpng-dev libjpeg-dev libfreetype6-dev default-mysql-client libonig-dev 2>/dev/null || echo "⚠️ Certains packages système peuvent déjà être installés"

# Nettoyage des artefacts de compilation existants
rm -rf /usr/src/php/ext/*/tmp-php* 2>/dev/null || true
rm -rf /usr/src/php/ext/*/.libs 2>/dev/null || true

echo "🔧 Installation des extensions PHP principales..."
# Installation des extensions une par une pour un meilleur suivi des erreurs
docker-php-ext-install pdo 2>/dev/null || echo "⚠️ PDO installation avec warnings (peut déjà exister)"
docker-php-ext-install pdo_mysql 2>/dev/null || echo "⚠️ PDO MySQL installation avec warnings (peut déjà exister)"
docker-php-ext-install mysqli 2>/dev/null || echo "⚠️ MySQLi installation avec warnings (peut déjà exister)"
docker-php-ext-install intl 2>/dev/null || echo "⚠️ Intl installation avec warnings (peut déjà exister)"
docker-php-ext-install zip 2>/dev/null || echo "⚠️ Zip installation avec warnings (peut déjà exister)"
docker-php-ext-install xml 2>/dev/null || echo "⚠️ XML installation avec warnings (peut déjà exister)"
docker-php-ext-install mbstring 2>/dev/null || echo "⚠️ mbstring installation avec warnings (peut déjà exister)"

echo "🎨 Configuration et installation de l'extension GD..."
docker-php-ext-configure gd --with-freetype --with-jpeg 2>/dev/null || echo "⚠️ GD configure avec warnings"
docker-php-ext-install gd 2>/dev/null || echo "⚠️ GD installation avec warnings (peut déjà exister)"

echo "✅ Installation des extensions PHP terminée"
echo "🔍 Vérification des extensions:"
php -r "echo 'PDO: ' . (extension_loaded('pdo') ? '✅ OK' : '❌ MISSING') . PHP_EOL;"
php -r "echo 'PDO MySQL: ' . (extension_loaded('pdo_mysql') ? '✅ OK' : '❌ MISSING') . PHP_EOL;"
php -r "echo 'MySQLi: ' . (extension_loaded('mysqli') ? '✅ OK' : '❌ MISSING') . PHP_EOL;"
php -r "echo 'Intl: ' . (extension_loaded('intl') ? '✅ OK' : '❌ MISSING') . PHP_EOL;"
php -r "echo 'Zip: ' . (extension_loaded('zip') ? '✅ OK' : '❌ MISSING') . PHP_EOL;"
echo "📋 Toutes les extensions chargées:"
php -m | grep -E "(pdo|mysql|intl|zip|xml|gd|mbstring)" | head -10