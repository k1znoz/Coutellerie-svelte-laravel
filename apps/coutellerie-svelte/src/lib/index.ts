// Re-exports pour simplifier les imports
// Cela permet d'importer depuis '$lib' au lieu de '$lib/types' ou '$lib/components'

// Types
export * from './types';

// Composants
export { default as HeroSection } from './components/HeroSection.svelte';
export { default as GalleryTeaser } from './components/home/GalleryTeaser.svelte';

// Exports from about components
export { AboutSection, ValueCard, ValuesGrid } from './components/about';

// Exports from gallery components
export * from './components/gallery';
export * from './services/index';
