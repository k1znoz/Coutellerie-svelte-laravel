# 🚂 Déploiement Railway Simple - Coutellerie Laravel

## 🎯 **Architecture Simplifiée**

Seulement **2 services Railway** nécessaires :
1. **App Service** - Laravel API + Filament Admin (tout en un)
2. **Database** - MySQL géré par Railway

## 🚀 **Étapes de Déploiement**

### 1. Créer le Projet Railway
```bash
cd services/coutellerie-laravel
railway init
```

### 2. Créer la Base MySQL
1. Railway Dashboard → **"+ New Service"**
2. **"Database → MySQL"** 
3. Nommer : `coutellerie-database`

### 3. Déployer l'App Principal
1. **"+ New Service → GitHub Repo"**
2. Repo : `k1znoz/Coutellerie-svelte-laravel`
3. Root Directory : `/services/coutellerie-laravel`
4. Nommer : `coutellerie-app`

### 4. Variables d'Environnement
```bash
# Variables critiques à ajouter dans Railway
APP_KEY=base64:VOTRE_CLE_GENEREE
DB_URL=${{coutellerie-database.DATABASE_URL}}

# Le reste est géré par railway.toml automatiquement
```

### 5. Générer le Domaine
Settings → Networking → **"Generate Domain"**

## ✅ **C'est tout !** 

Votre application sera disponible avec :
- 🌐 API REST : `/api/knives`
- 👤 Admin Panel : `/admin`
- 📝 Contact : `/api/contact`

## 🔧 **Si vous avez besoin plus tard...**

**Ajouter Workers/Cron seulement si** :
- Envoi d'emails massifs
- Traitement d'images lourd
- Tâches programmées complexes  

Pour l'instant, votre app fonctionne parfaitement en **mode simple** ! 🎉