# 🚂 Railway avec NIXPACKS - Coutellerie Laravel

## ✨ **C'est SIMPLE maintenant !**

### 1. Service Railway à créer :
- **Repository** : `k1znoz/Coutellerie-svelte-laravel`  
- **Root Directory** : `/services/coutellerie-laravel`
- **Builder** : NIXPACKS (automatique)

### 2. Variables à ajouter sur Railway :
```bash
# Variables depuis AllVar.txt - section Laravel
APP_DEBUG="false"
APP_ENV="production"  
APP_KEY="${{shared.APP_KEY}}"
# ... (copier le reste depuis AllVar.txt)
```

### 3. NIXPACKS fait automatiquement :
- ✅ Détecte PHP 8.3 via composer.json
- ✅ Installe les extensions PHP (ext-intl, ext-zip, etc.)
- ✅ Lance `composer install`
- ✅ Configure nginx + PHP-FPM  
- ✅ Root directory `/app/public` (Laravel standard)

## 🎯 **Plus besoin de** :
- ❌ railway.toml
- ❌ .railpack  
- ❌ Scripts complexes
- ❌ Configurations manuelles

## 🚀 **Déployer maintenant !**