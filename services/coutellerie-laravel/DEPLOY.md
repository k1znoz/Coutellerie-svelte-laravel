# 🚀 Déploiement Railway - SIMPLE

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

Railway détecte automatiquement Laravel et utilise nos fichiers :
- `Procfile` → Lance `deploy.sh`
- `deploy.sh` → Install + migrate + démarre
- `railway.toml` → Config Railway

✅ **Structure optimisée pour Railway !**