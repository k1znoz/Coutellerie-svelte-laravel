# Coutellerie Sanity Studio

Studio Sanity pour gérer le contenu du projet.

## Installation

```bash
npm install
```

## Variables d'environnement

Copier `.env.example` vers `.env` puis renseigner:

- `SANITY_STUDIO_PROJECT_ID`
- `SANITY_STUDIO_DATASET`

`SANITY_STUDIO_PROJECT_ID` doit être l'ID Sanity brut (ex: `focn0owe`), pas une URL et sans underscore.

## Lancer le Studio

```bash
npm run dev
```

## Schémas inclus

- `knife`: contenu de la galerie (titre, catégorie, description, images, etc.).
- `contactMessage`: messages envoyés depuis le formulaire de contact Svelte.
