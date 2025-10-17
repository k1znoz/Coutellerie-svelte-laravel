# 🚀 Guide de déploiement Railway

## Pré-requis

- Compte Railway configuré
- Service MySQL activé sur Railway
- Variables d'environnement configurées

## Variables d'environnement nécessaires

Dans votre dashboard Railway, configurez ces variables :

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_URL=https://votre-app.up.railway.app

# MySQL (automatiquement configurées par Railway)
DB_CONNECTION=mysql
DB_HOST=${{MYSQLHOST}}
DB_PORT=${{MYSQLPORT}}
DB_DATABASE=${{MYSQLDATABASE}}
DB_USERNAME=${{MYSQLUSER}}
DB_PASSWORD=${{MYSQLPASSWORD}}

# Configuration cache et session
CACHE_DRIVER=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

## Déploiement automatique

1. **Pousser le code** : Railway détecte automatiquement les changements
2. **Build** : Utilise le `railway.toml` et `Procfile`
3. **Déploiement** : Exécute `deploy.sh`

## Structure des fichiers

- `railway.toml` : Configuration Railway
- `Procfile` : Commande de démarrage
- `deploy.sh` : Script de déploiement principal
- `setup-env.sh` : Helper pour les variables d'environnement

## Commandes utiles

```bash
# Générer une nouvelle APP_KEY
php artisan key:generate

# Vérifier les migrations
php artisan migrate:status

# Nettoyer le cache
php artisan cache:clear
php artisan config:clear
```

## Dépannage

- **Erreur de connexion MySQL** : Vérifiez que le service MySQL est actif
- **APP_KEY manquante** : Générez une clé avec `php artisan key:generate`
- **Erreur de build** : Vérifiez les logs Railway

## Architecture du projet

```
Coutellerie-svelte-laravel/
├── apps/coutellerie-svelte/    # Frontend Svelte
├── services/coutellerie-laravel/ # Backend Laravel
├── deploy.sh                   # Script de déploiement
├── railway.toml               # Configuration Railway
└── Procfile                   # Commande de démarrage
```
