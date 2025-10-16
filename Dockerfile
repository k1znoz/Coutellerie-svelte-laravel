# Dockerfile multi-stage pour Svelte + Laravel sur Railway
FROM node:18-alpine as frontend-builder

# Copier et construire l'application Svelte
WORKDIR /app/frontend
COPY apps/coutellerie-svelte/package*.json ./
RUN npm ci

COPY apps/coutellerie-svelte/ ./
RUN npx svelte-kit sync
RUN npm run build

# Stage principal PHP/Laravel
FROM php:8.2-fpm-alpine

# Installer les dépendances système
RUN apk add --no-cache \
    nginx \
    supervisor \
    zip \
    unzip \
    git \
    curl \
    oniguruma-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    xml \
    zip \
    && rm -rf /var/cache/apk/*

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Créer le répertoire de travail
WORKDIR /var/www

# Copier et installer les dépendances Laravel
COPY services/coutellerie-laravel/composer.json services/coutellerie-laravel/composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copier l'application Laravel
COPY services/coutellerie-laravel/ ./

# Copier les assets buildés du frontend
COPY --from=frontend-builder /app/frontend/build ./public/build

# Configurer les permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Copier la configuration Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/supervisord.conf

# Exposer le port
EXPOSE 8080

# Commande de démarrage
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]