# Déploiement sur Railway 🚅

## Configuration automatique

Ce projet est configuré pour se déployer automatiquement sur Railway avec les fichiers suivants :

- `railway.toml` - Configuration Railway
- `nixpacks.toml` - Configuration Nixpacks pour le build
- `Dockerfile` - Image Docker personnalisée (alternative)
- `build.sh` - Script de construction
- `start.sh` - Script de démarrage
- `.env.railway` - Variables d'environnement pour Railway

## Étapes de déploiement

### 1. Préparer Railway

1. Créer un compte sur [Railway.app](https://railway.app)
2. Connecter votre repository GitHub
3. Créer un nouveau projet depuis GitHub

### 2. Configurer la base de données

1. Ajouter un service MySQL depuis Railway Dashboard
2. Railway générera automatiquement les variables :
   - `MYSQL_HOST`
   - `MYSQL_PORT`
   - `MYSQL_DATABASE`
   - `MYSQL_USER`
   - `MYSQL_PASSWORD`

### 3. Variables d'environnement à configurer

Dans Railway Dashboard > Variables :

```bash
APP_KEY=base64:VotreCléGénéréeAvecPhpArtisanKeyGenerate
APP_URL=https://votre-app.railway.app
APP_ENV=production
APP_DEBUG=false
```

### 4. Générer la clé d'application

Localement, exécutez :

```bash
cd services/coutellerie-laravel
php artisan key:generate --show
```

Copiez la clé générée dans Railway Variables.

### 5. Déployer

1. Push votre code sur GitHub
2. Railway détectera automatiquement les changements
3. Le build se lancera automatiquement
4. L'application sera disponible sur votre URL Railway

## Structure du déploiement

```
Build Process:
1. 📦 Build Svelte app (apps/coutellerie-svelte)
2. 📦 Install Laravel dependencies (services/coutellerie-laravel)
3. 📋 Copy Svelte build to Laravel public
4. ⚡ Optimize Laravel (cache configs, routes, views)
5. 🗄️ Run database migrations
6. 🌟 Start Laravel server
```

## Monitoring

- Logs disponibles dans Railway Dashboard
- Métriques de performance automatiques
- Déploiements automatiques sur push

## Dépannage

### Build fails

- Vérifiez les logs dans Railway Dashboard
- Assurez-vous que toutes les variables d'environnement sont définies

### Database connection issues

- Vérifiez que MySQL service est actif
- Vérifiez les variables de connexion DB

### Static assets not loading

- Vérifiez que le build Svelte s'est terminé correctement
- Les assets sont copiés dans `public/app/`

## URLs importantes

- **Application** : `https://votre-app.railway.app`
- **Admin Filament** : `https://votre-app.railway.app/admin`
- **API** : `https://votre-app.railway.app/api`
