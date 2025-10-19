#!/bin/bash

# Script de déploiement Railway pour Laravel + Filament
echo "🚂 Railway Laravel + Filament Deployment"
echo "========================================"

# Install dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Environment setup  
echo "⚙️ Setting up environment..."
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

# Complete Railway setup using our custom command
echo "� Running Railway setup..."
php artisan railway:setup || echo "⚠️ Railway setup failed"

echo "✅ Railway deployment complete!"