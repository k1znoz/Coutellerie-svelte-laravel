# Dockerfile simple pour Railway
FROM php:8.3-cli

# Installer Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Installer les dépendances système pour les extensions PHP
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Installer extensions PHP nécessaires
RUN docker-php-ext-install \
    pdo_mysql \
    intl \
    zip

# Définir le répertoire de travail
WORKDIR /app

# Copier les fichiers du projet
COPY . .

# Build Svelte
RUN cd apps/coutellerie-svelte && npm ci && npx svelte-kit sync && npm run build

# Copier build Svelte vers Laravel public
RUN mkdir -p services/coutellerie-laravel/public/build && \
    cp -r apps/coutellerie-svelte/build/* services/coutellerie-laravel/public/build/ 2>/dev/null || true

# Installer dépendances Laravel
RUN cd services/coutellerie-laravel && composer install --no-dev --optimize-autoloader --no-interaction

# Exposer le port
EXPOSE 8000

# Définir le répertoire de travail Laravel
WORKDIR /app/services/coutellerie-laravel

# Commande de démarrage
CMD php artisan migrate --force --no-interaction && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}