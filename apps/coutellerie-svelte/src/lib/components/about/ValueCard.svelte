<script lang="ts">
	import type { Value } from '$lib/types';
	import '$lib/styles/about.css';

	export let value: Value;
</script>

<div class="value-item">
	<i class={value.icon}></i>
	<h3>{value.title}</h3>
	<p>{value.description}</p>
</div>

<style>
	/* Les styles ont été déplacés vers about.css */
</style>
