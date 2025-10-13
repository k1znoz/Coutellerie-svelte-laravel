<script lang="ts">
	import type { Knife, GalleryItem } from '$lib/types';

	export let knives: Knife[] = [];
	export let currentCategory: string = 'Tous';
	export let currentMaterial: string = 'Tous';

	// Callback pour envoyer les résultats filtrés au parent
	export let onFilteredItemsChange: (items: GalleryItem[]) => void = () => {};
	export let onCategoryChange: (category: string) => void = () => {};
	export let onMaterialChange: (material: string) => void = () => {};

	// Génération automatique des catégories et matériaux à partir des données
	$: categories = generateCategories(knives);
	$: materials = generateMaterials(knives);

	// Filtrage réactif basé sur les données, la catégorie et le matériau courants
	$: filteredItems = filterKnives(knives, currentCategory, currentMaterial);

	// Notifier le parent quand les éléments filtrés changent
	$: onFilteredItemsChange(filteredItems);

	function generateCategories(knifeData: Knife[]): string[] {
		if (!knifeData || knifeData.length === 0) {
			return ['Tous'];
		}
		const uniqueCategories = [...new Set(knifeData.map((knife) => knife.category))];
		return ['Tous', ...uniqueCategories.sort()];
	}

	function generateMaterials(knifeData: Knife[]): string[] {
		if (!knifeData || knifeData.length === 0) {
			return ['Tous'];
		}
		const uniqueMaterials = [
			...new Set(
				knifeData
					.map((knife) => knife.material)
					.filter((material): material is string => Boolean(material))
			)
		];
		return ['Tous', ...uniqueMaterials.sort()];
	}

	function filterKnives(knifeData: Knife[], category: string, material: string): GalleryItem[] {
		if (!knifeData || knifeData.length === 0) {
			return [];
		}

		let dataToFilter = knifeData;

		// Filtrer par catégorie
		if (category !== 'Tous') {
			dataToFilter = dataToFilter.filter((knife) => knife.category === category);
		}

		// Filtrer par matériau
		if (material !== 'Tous') {
			dataToFilter = dataToFilter.filter((knife) => knife.material === material);
		}

		return dataToFilter.map((knife) => ({
			id: knife.id,
			title: knife.title,
			category: knife.category,
			material: knife.material,
			images: knife.images || [],
			primaryImage: knife.images && knife.images.length > 0 ? knife.images[0] : undefined
		}));
	}

	function selectCategory(category: string) {
		currentCategory = category;
		onCategoryChange(category);
	}

	function selectMaterial(material: string) {
		currentMaterial = material;
		onMaterialChange(material);
	}
</script>

<div class="gallery-filter">
	<div class="filter-section">
		<h3>Filtrer par catégorie</h3>
		<div class="filter-buttons">
			{#each categories as category (category)}
				<button
					class:active={currentCategory === category}
					on:click={() => selectCategory(category)}
				>
					{category}
				</button>
			{/each}
		</div>
	</div>

	<div class="filter-section">
		<h3>Filtrer par matériau</h3>
		<div class="filter-buttons">
			{#each materials as material (material)}
				<button
					class:active={currentMaterial === material}
					on:click={() => selectMaterial(material)}
				>
					{material}
				</button>
			{/each}
		</div>
	</div>
</div>

<style>
	.gallery-filter {
		margin-bottom: 2rem;
		margin-top: 1rem;
	}

	.filter-section {
		margin-bottom: 1.5rem;
	}

	.filter-section h3 {
		text-align: center;
		margin-bottom: 1rem;
		color: var(--text-color);
		font-size: 1.1rem;
		font-weight: 600;
	}

	.filter-buttons {
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
		gap: 0.75rem;
	}

	.filter-buttons button {
		padding: 0.5rem 1.5rem;
		background-color: transparent;
		border: 2px solid var(--primary-color);
		color: var(--primary-color);
		border-radius: 2rem;
		cursor: pointer;
		transition: all 0.3s;
		font-weight: 600;
		font-size: 0.9rem;
	}

	.filter-buttons button.active,
	.filter-buttons button:hover {
		background-color: var(--primary-color);
		color: var(--white);
	}

	@media (max-width: 768px) {
		.filter-buttons button {
			padding: 0.4rem 1rem;
			font-size: 0.8rem;
		}

		.filter-section h3 {
			font-size: 1rem;
		}
	}
</style>
