# 🚀 Déploiement Railway - AUTOMATIQUE

## Configuration Railway

1. **Root Directory** : `services/coutellerie-laravel`
2. **Service MySQL** : Activé
3. **Variables** à configurer :

```env
APP_KEY=base64:YOUR_APP_KEY_HERE
DB_CONNECTION=mysql
DB_HOST=${{MYSQLHOST}}
DB_PORT=${{MYSQLPORT}}
DB_DATABASE=${{MYSQLDATABASE}}
DB_USERNAME=${{MYSQLUSER}}
DB_PASSWORD=${{MYSQLPASSWORD}}
```

## C'est tout !

Railway avec Nixpacks fait TOUT automatiquement :

-   ✅ Force PHP 8.3 (via `.nixpacks.toml`)
-   ✅ Installe Composer automatiquement
-   ✅ Lance `composer install --no-dev --optimize-autoloader`
-   ✅ Exécute `php artisan migrate --force`
-   ✅ Démarre le serveur Laravel

**Configuration :** `.nixpacks.toml` force la détection PHP
