# Coutellerie Svelte

Frontend SvelteKit de la coutellerie, connecté à Sanity pour la galerie et les messages de contact.

## Variables d'environnement

Copier `.env.example` vers `.env` puis renseigner:

- `PUBLIC_SANITY_PROJECT_ID`
- `PUBLIC_SANITY_DATASET`
- `PUBLIC_SANITY_API_VERSION`
- `SANITY_API_TOKEN` (token write pour enregistrer les messages de contact via `src/routes/api/contact/+server.ts`)

## Développement

```bash
npm install
npm run dev
```

## Build

```bash
npm run build
```

## Notes d'architecture

- Les couteaux de la galerie sont lus directement depuis Sanity via `src/lib/services/api.ts`.
- Les messages de contact sont enregistrés via un endpoint SvelteKit (`/api/contact`) puis créés dans Sanity.
