<script lang="ts">
	// Définir un type pour les items de la galerie
	interface GalleryItem {
		id: string | number;
		title: string;
		category: string;
		images?: string[];
		primaryImage?: string;
	}

	// Fonction pour obtenir la première image disponible
	function getPrimaryImage(item: GalleryItem): string {
		if (item.primaryImage) return item.primaryImage;
		if (item.images && item.images.length > 0) return item.images[0];
		return '/images/placeholder.jpg';
	}

	export let items: GalleryItem[] = [];

	// Remplacer createEventDispatcher par une fonction callback
	export let onItemSelect: (item: GalleryItem) => void = () => {};

	function selectItem(item: GalleryItem) {
		onItemSelect(item);
	}
</script>

<div class="gallery-grid">
	{#each items as item (item.id)}
		<div
			class="gallery-item"
			on:click={() => selectItem(item)}
			on:keydown={(e) => e.key === 'Enter' && selectItem(item)}
			role="button"
			tabindex="0"
			aria-label="Voir le détail de {item.title}"
		>
			<div class="gallery-image">
				<img src={getPrimaryImage(item)} alt={item.title} loading="lazy" />
				{#if item.images && item.images.length > 1}
					<div class="image-count">
						<span>+{item.images.length - 1}</span>
					</div>
				{/if}
			</div>
			<div class="gallery-info">
				<h3>{item.title}</h3>
				<p class="category">{item.category}</p>
			</div>
		</div>
	{/each}
</div>

<style>
	.gallery-grid {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 1.5rem;
	}

	.gallery-item {
		border-radius: 0.5rem;
		overflow: hidden;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
		cursor: pointer;
		transition: transform 0.3s;
	}

	.gallery-item:hover {
		transform: translateY(-5px);
	}

	.gallery-image {
		height: 250px;
		overflow: hidden;
		position: relative;
	}

	.gallery-image img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		transition: transform 0.3s;
	}

	.gallery-item:hover .gallery-image img {
		transform: scale(1.05);
	}

	.image-count {
		position: absolute;
		top: 8px;
		right: 8px;
		background-color: rgba(0, 0, 0, 0.7);
		color: white;
		padding: 4px 8px;
		border-radius: 12px;
		font-size: 0.8rem;
		font-weight: bold;
	}

	.gallery-info {
		padding: 1.25rem;
		background-color: var(--white);
	}

	.gallery-info h3 {
		margin-bottom: 0.5rem;
		color: var(--primary-color);
	}

	.category {
		color: var(--grey);
		font-size: 0.9rem;
	}

	@media (max-width: 992px) {
		.gallery-grid {
			grid-template-columns: repeat(2, 1fr);
		}
	}

	@media (max-width: 576px) {
		.gallery-grid {
			grid-template-columns: 1fr;
		}
	}
</style>
