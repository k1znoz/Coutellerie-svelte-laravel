# Étape 1: Base avec PHP et Apache
FROM php:8.3-apache

# Mettre à jour l'environnement et installer les dépendances système et extensions PHP pour Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    libpq-dev \
    libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql zip exif pcntl bcmath intl

# Installer Composer (gestionnaire de dépendances PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de dépendances et les installer (optimisation du cache Docker)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist

# Copier le reste de l'application
COPY . .

# Exécuter le reste des scripts d'installation de composer (comme l'optimisation de l'autoload)
RUN composer install --no-dev --no-interaction

# Générer la clé d'application (nécessaire pour que artisan fonctionne)
# On copie d'abord .env.example pour que la commande artisan ne échoue pas.
RUN cp .env.example .env
RUN php artisan key:generate

# La mise en cache est désactivée pour permettre aux variables d'environnement de Render d'être prises en compte au runtime.
# RUN php artisan config:cache
# RUN php artisan route:cache
# RUN php artisan view:cache

# Définir les permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configurer Apache pour pointer vers le dossier public de Laravel
RUN a2enmod rewrite
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Exposer le port 80
EXPOSE 80

# Copier et rendre exécutable le script de démarrage
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Définir le script de démarrage comme commande par défaut
CMD ["entrypoint.sh"]
