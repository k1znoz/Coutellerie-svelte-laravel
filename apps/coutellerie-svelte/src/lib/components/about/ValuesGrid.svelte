<script lang="ts">
	import type { Value } from '$lib/types';
	import ValueCard from './ValueCard.svelte';
	import '$lib/styles/about.css';

	export let values: Value[];
	export let title: string = 'Nos valeurs';
</script>

<section class="values-section">
	<div class="container">
		<h2>{title}</h2>
		<div class="values-grid">
			{#each values as value (value.title)}
				<ValueCard {value} />
			{/each}
		</div>
	</div>
</section>

<style>
	.container {
		max-width: 1200px;
		margin: 0 auto;
	}

	h2 {
		text-align: center;
		margin-bottom: 2rem;
		font-size: 2rem;
	}
</style>
