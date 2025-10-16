#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Navigate to Laravel directory
cd services/coutellerie-laravel

# Copy production environment file if in production
if [ ! -f ".env" ] && [ -f ".env.production" ]; then
    echo "🔧 Setting up production environment..."
    cp .env.production .env
fi

# Always ensure Laravel dependencies are installed
echo "📦 Installing/verifying Laravel dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Verify that vendor/autoload.php exists
if [ ! -f "vendor/autoload.php" ]; then
    echo "❌ Error: vendor/autoload.php not found after composer install"
    echo "Current directory: $(pwd)"
    echo "Contents of current directory:"
    ls -la
    echo "Contents of vendor directory (if exists):"
    ls -la vendor/ 2>/dev/null || echo "vendor directory does not exist"
    exit 1
fi

echo "✅ Laravel dependencies successfully installed"

# Copy Svelte build to Laravel public (if exists)
if [ -d "../apps/coutellerie-svelte/build" ]; then
    echo "📋 Copying Svelte build to Laravel public..."
    mkdir -p public/app
    cp -r ../apps/coutellerie-svelte/build/* public/app/ 2>/dev/null || true
fi

# Wait for MySQL service to be ready and test connection
echo "🗄️ Waiting for MySQL database to be ready..."
sleep 5

# Debug: Show all environment variables related to database
echo "🔍 Debugging database configuration..."
echo "=== Environment Variables ==="
env | grep -E "(DB_|MYSQL)" | sort
echo "=========================="

# Show Laravel database configuration
echo "📋 Laravel Database Config:"
php artisan config:show database.connections.mysql 2>/dev/null || echo "Failed to show database config"

# Simple database connection test
echo "🔍 Testing database connection..."
php artisan tinker --execute="
try {
    \$pdo = DB::connection()->getPdo();
    echo 'SUCCESS: Database connected!';
    echo 'Connection info: ' . \$pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS);
} catch (Exception \$e) {
    echo 'FAILED: Database connection error: ' . \$e->getMessage();
    echo 'Config database: ' . config('database.connections.mysql.database', 'NOT_SET');
    echo 'Config host: ' . config('database.connections.mysql.host', 'NOT_SET');
    echo 'Config port: ' . config('database.connections.mysql.port', 'NOT_SET');
    echo 'Config username: ' . config('database.connections.mysql.username', 'NOT_SET');
}
"

# Show current migration status
echo "📋 Checking current migration status..."
php artisan migrate:status --no-interaction || echo "No migrations table found yet"

# Run migrations
echo "📊 Running database migrations..."
php artisan migrate --force --no-interaction

# Show final migration status
echo "✅ Final migration status:"
php artisan migrate:status --no-interaction

# Seed database with initial data (only if tables are empty)
echo "🌱 Checking if database seeding is needed..."
if php artisan tinker --execute="echo (DB::table('users')->count() === 0) ? 'SEED_NEEDED' : 'SEED_SKIP';" | grep -q "SEED_NEEDED"; then
    echo "🌱 Seeding database with initial data..."
    php artisan db:seed --no-interaction --force || echo "Seeding completed or no seeders available"
else
    echo "✅ Database already contains data, skipping seeding"
fi

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear

echo "🌟 Starting Laravel server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}