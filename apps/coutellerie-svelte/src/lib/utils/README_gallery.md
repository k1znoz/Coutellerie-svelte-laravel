# 🎨 Système de Galerie Automatique

Ce système gère automatiquement les images de la galerie avec sélection aléatoire pour le teaser.

## 📁 Structure des fichiers

```
src/lib/utils/
├── galleryUtils.ts          # Logique principale du système
└── README_gallery.md        # Cette documentation
```

## 🚀 Utilisation quotidienne

### Ajouter une nouvelle création

1. **Placez votre image** dans `static/images/gallery/`
2. **Ajoutez le nom** dans `imageFiles[]` dans `galleryUtils.ts`
3. **C'est tout !** Le système génère automatiquement :
   - Description alt descriptive
   - Intégration dans la sélection aléatoire

### Exemple d'ajout

```typescript
// Dans galleryUtils.ts, ajoutez à imageFiles :
const imageFiles = [
	// ... images existantes
	'mon-nouveau-couteau-chef.webp' // ← Nouvelle image
];
```

Résultat automatique :

- **Alt** : "Création artisanale de coutellerie - mon-nouveau-couteau-chef"
- **Visible** dans le teaser aléatoire

## 🎯 Nommage des fichiers

### ✅ Recommandations

- Utilisez des noms descriptifs : `damascus-chef-premium.webp`
- Évitez les espaces : `couteau-chasse-bronze.webp` plutôt que `couteau chasse bronze.webp`
- Format recommandé : `.webp` pour l'optimisation

## 🎲 Fonctionnement du teaser

- **Sélection** : 3 images aléatoires à chaque chargement
- **Pool** : Toutes les images de la galerie
- **Variété** : Plus de 364 combinaisons possibles (14 images)
- **Performance** : Sélection unique au montage du composant

## 📊 Avantages du système

### ✅ Maintenance simplifiée

- Ajout d'images sans code complexe
- Pas de duplication de données
- Système épuré sans métadonnées complexes

### ✅ Évolutivité

- Facilement extensible
- Configuration centralisée
- Système modulaire

### ✅ Performance

- Pas de requêtes réseau
- Génération côté client
- Optimisation automatique

## 🔄 Workflow de production

1. **Création** → Photographiez votre nouvelle pièce
2. **Optimisation** → Convertissez en .webp (recommandé)
3. **Upload** → Placez dans `static/images/gallery/`
4. **Configuration** → Ajoutez à `imageFiles[]`
5. **Déploiement** → Le teaser utilise automatiquement la nouvelle image

## 🎨 Personnalisation future

Le système est conçu pour évoluer facilement :

- **Catégories** → Grouper par type de couteau
- **Metadata** → Ajouter descriptions, prix, dimensions
- **Filtres** → Sélection par critères spécifiques
- **API Backend** → Migration vers base de données

---

_Ce système garantit une maintenance minimale tout en conservant une expérience utilisateur riche et variée._
