<script lang="ts">
	import { goto } from '$app/navigation';
	import { onMount } from 'svelte';
	import { getAllKnives } from '$lib/services/api'; // Utiliser l'API Laravel
	import type { Knife } from '$lib/types';

	let featuredImages: { src: string; alt: string; id: number }[] = [];
	let loading = true;

	/**
	 * Sélectionne 3 couteaux aléatoires depuis l'API
	 */
	async function loadFeaturedKnives() {
		try {
			const knives: Knife[] = await getAllKnives();

			if (knives.length === 0) return;

			// Sélection aléatoire de 3 couteaux
			const shuffled = [...knives].sort(() => Math.random() - 0.5);
			const selectedKnives = shuffled.slice(0, 3);

			// Mapper vers le format attendu
			featuredImages = selectedKnives.map((knife) => ({
				src: knife.images && knife.images.length > 0 ? knife.images[0] : '/images/placeholder.webp',
				alt: `Création artisanale - ${knife.title}`,
				id: knife.id
			}));
		} catch (error) {
			console.error('Erreur lors du chargement des couteaux:', error);
			featuredImages = []; // Fallback vers aucune image
		} finally {
			loading = false;
		}
	}

	function handleImageClick(id: number) {
		goto('/Gallery');
	}

	onMount(() => {
		loadFeaturedKnives();
	});
</script>

<section class="gallery-teaser">
	<div class="container">
		<div class="teaser-header">
			<h2>Mes Créations</h2>
			<p>Découvrez quelques-unes de mes réalisations artisanales</p>
		</div>

		{#if loading}
			<div class="loading">
				<p>Chargement des créations...</p>
			</div>
		{:else if featuredImages.length === 0}
			<div class="no-images">
				<p>Aucune création disponible pour le moment.</p>
			</div>
		{:else}
			<div class="featured-grid">
				{#each featuredImages as image, index}
					<div
						class="featured-item"
						role="button"
						tabindex="0"
						on:click={() => handleImageClick(image.id)}
						on:keydown={(e) => e.key === 'Enter' && handleImageClick(image.id)}
					>
						<img
							src={image.src}
							alt={image.alt}
							loading="lazy"
							on:error={(e) => {
								// Correction TypeScript : typage explicite
								const target = e.target as HTMLImageElement;
								if (target) {
									target.src = '/images/placeholder.webp';
								}
							}}
						/>
					</div>
				{/each}
			</div>
		{/if}

		<div class="cta-section">
			<button class="cta-button" on:click={() => goto('/Gallery')}>
				<i class="fas fa-images"></i>
				Découvrir toute la galerie
			</button>
		</div>
	</div>
</section>

<style>
	.gallery-teaser {
		padding: 4rem 2rem;
		background-color: var(--light-grey, #f8f9fa);
	}

	.container {
		max-width: 1200px;
		margin: 0 auto;
	}

	.teaser-header {
		text-align: center;
		margin-bottom: 3rem;
	}

	.teaser-header h2 {
		font-size: 2.5rem;
		color: var(--primary-color, #8b4513);
		margin-bottom: 1rem;
	}

	.teaser-header p {
		font-size: 1.2rem;
		color: var(--grey, #666);
		max-width: 600px;
		margin: 0 auto;
	}

	.loading,
	.no-images {
		padding: 2rem;
		color: var(--text-muted, #666);
	}

	.featured-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
		gap: 2rem;
		margin-bottom: 3rem;
	}

	.featured-item {
		position: relative;
		aspect-ratio: 4/3;
		border-radius: 0.5rem;
		overflow: hidden;
		cursor: pointer;
		transition:
			transform 0.3s ease,
			box-shadow 0.3s ease;
		box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
	}

	.featured-item:hover {
		transform: translateY(-5px);
		box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
	}

	.featured-item img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		transition: transform 0.3s ease;
	}

	.featured-item:hover img {
		transform: scale(1.05);
	}

	.cta-section {
		text-align: center;
	}

	.cta-button {
		background: var(--primary-color, #8b4513);
		color: white;
		border: none;
		padding: 1rem 2rem;
		font-size: 1.1rem;
		font-weight: 600;
		border-radius: 0.5rem;
		cursor: pointer;
		transition:
			background-color 0.3s ease,
			transform 0.2s ease;
		display: inline-flex;
		align-items: center;
		gap: 0.5rem;
	}

	.cta-button:hover {
		background: var(--primary-dark, #6b3410);
		transform: translateY(-2px);
	}

	.cta-button i {
		font-size: 1.2rem;
	}

	/* Responsive styles */
	@media (max-width: 768px) {
		.gallery-teaser {
			padding: 3rem 1rem;
		}

		.teaser-header h2 {
			font-size: 2rem;
		}

		.featured-grid {
			grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
			gap: 1.5rem;
		}
	}

	@media (max-width: 480px) {
		.featured-grid {
			grid-template-columns: 1fr;
		}
	}
</style>
