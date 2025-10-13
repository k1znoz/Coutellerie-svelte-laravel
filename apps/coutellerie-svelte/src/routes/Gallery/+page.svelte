<script lang="ts">
	import HeroSection from '$lib/components/HeroSection.svelte';
	import GalleryFilter from '$lib/components/gallery/GalleryFilter.svelte';
	import GalleryGrid from '$lib/components/gallery/GalleryGrid.svelte';
	import ImageLightbox from '$lib/components/gallery/ImageLightbox.svelte';
	import CTASection from '$lib/components/gallery/CTASection.svelte';
	import { onMount } from 'svelte';
	import type { Knife, HeroPropsGallery, GalleryItem } from '$lib/types';
	import { getAllKnives } from '$lib/services/api';
	// Configuration de la section hero pour la page Galerie
	const heroProps: HeroPropsGallery = {
		title: 'Galerie',
		subtitle: 'Découvrez mes créations uniques',
		backgroundImage: '/images/background/HeroGallery.webp',
		type: 'gallery'
	};

	let knives: Knife[] = [];
	let filteredKnives: GalleryItem[] = [];
	let currentCategory = 'Tous';
	let currentMaterial = 'Tous';
	let lightboxOpen = false;
	let currentImage: Knife | null = null;
	let loading = true;
	let error = false;

	// Fonction pour recevoir les éléments filtrés du composant GalleryFilter
	function handleFilteredItemsChange(items: GalleryItem[]) {
		filteredKnives = items;
	}

	// Fonction pour gérer le changement de catégorie
	function handleCategoryChange(category: string) {
		currentCategory = category;
	}

	// Fonction pour gérer le changement de matériau
	function handleMaterialChange(material: string) {
		currentMaterial = material;
	}

	// Fonction pour ouvrir la lightbox
	function handleItemSelect(item: GalleryItem) {
		currentImage = knives.find((knife) => knife.id === item.id) || null;
		lightboxOpen = true;
	}

	// Fonction pour fermer la lightbox
	function handleCloseLightbox() {
		lightboxOpen = false;
		currentImage = null;
	}

	// Récupération des données depuis l'API
	async function fetchKnives() {
		try {
			loading = true;
			error = false;
			const apiKnives = await getAllKnives();
			knives = apiKnives;
			// filteredKnives sera mis à jour automatiquement par GalleryFilter
		} catch (err) {
			error = true;
			knives = [];
			filteredKnives = [];
			console.error('Erreur lors du chargement des couteaux:', err);
		} finally {
			loading = false;
		}
	}

	onMount(() => {
		// Charger les données au montage du composant
		fetchKnives();
	});
</script>

<svelte:head>
	<title>Galerie - Coutellerie Artisanale</title>
</svelte:head>

<!-- Utilisation des composants modulaires -->
<HeroSection props={heroProps} />

<section class="gallery-section">
	<div class="container">
		{#if loading}
			<div class="loading-spinner">
				<p>Chargement des créations...</p>
			</div>
		{:else if error}
			<div class="error-message">
				<p>
					Impossible de charger les données depuis le serveur. Veuillez vérifier votre connexion.
				</p>
			</div>
		{/if}

		<GalleryFilter
			{knives}
			{currentCategory}
			{currentMaterial}
			onFilteredItemsChange={handleFilteredItemsChange}
			onCategoryChange={handleCategoryChange}
			onMaterialChange={handleMaterialChange}
		/>

		<GalleryGrid items={filteredKnives} onItemSelect={handleItemSelect} />
	</div>
</section>

<ImageLightbox isOpen={lightboxOpen} item={currentImage} onClose={handleCloseLightbox} />

<CTASection
	title="Vous avez un projet en tête ?"
	text="Je réalise également des couteaux sur mesure selon vos spécifications."
	buttonText="Me contacter"
	buttonLink="/Contact"
/>

<style>
	/* Les styles media queries spécifiques à cette page peuvent rester ici si nécessaire */
	@media (max-width: 576px) {
		.gallery-section {
			padding: 2rem 1rem;
		}
	}

	.loading-spinner {
		text-align: center;
		padding: 2rem;
		color: #333;
	}

	.error-message {
		text-align: center;
		padding: 1rem;
		margin-bottom: 1rem;
		background-color: #fff3cd;
		color: #856404;
		border-radius: 0.25rem;
	}
</style>
