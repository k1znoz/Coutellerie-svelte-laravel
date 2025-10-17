# 🚀 Déploiement Railway - SIMPLE

## Variables à configurer dans Railway

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

1. Connectez votre repo GitHub à Railway
2. Ajoutez le service MySQL
3. Configurez les variables ci-dessus
4. Railway fait le reste automatiquement

## Fichiers de déploiement

- `Procfile` → Lance `deploy.sh`
- `deploy.sh` → Install + migrate + démarre le serveur
- `railway.toml` → Config Railway
