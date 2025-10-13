<script lang="ts">
	import type { HeroProps } from '$lib/types';
	import '$lib/styles/heroSection.css';

	export let props: HeroProps;

	// Extraction des propriétés de l'objet props
	$: title = props.title;
	$: subtitle = props.subtitle;
	$: backgroundImage = props.backgroundImage || '/images/about-hero.jpg';
	$: ctaText = props.ctaText;
	$: ctaLink = props.ctaLink;

	// Pour distinguer les différents types de hero selon la page
	// Extrait du type depuis les props ou utilise 'about' par défaut
	$: type = props.type || 'about';
</script>

<section
	class="hero-small"
	style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url({backgroundImage})"
>
	<div class="hero-content">
		<h1>{title}</h1>
		<p>{subtitle}</p>

		<!-- ESPACE MODULABLE selon la page consultée -->
		{#if type === 'home' && ctaText && ctaLink}
			<a href={ctaLink} class="btn btn-primary">{ctaText}</a>
		{:else if type === 'about'}
			<!-- Style spécifique pour la page About -->
		{:else if type === 'gallery'}
			<!-- Style spécifique pour la page Galerie -->
		{:else if type === 'admin'}
			<!-- Style spécifique pour la page Admin -->
		{:else if type === 'contact'}
			<!-- Style spécifique pour la page Contact -->
		{/if}
	</div>
</section>

<style>
	.hero-small {
		min-height: 40vh;
		display: flex;
		align-items: center;
		justify-content: center;
		position: relative;
		padding: 3rem;
		text-align: center;
		color: var(--white);
		background-size: cover;
		background-position: center;
	}

	.hero-small h1 {
		font-size: 3rem;
		margin-bottom: 1rem;
	}

	.hero-small p {
		font-size: 1.25rem;
	}

	/* Style pour le bouton CTA */
	.btn {
		display: inline-block;
		padding: 0.75rem 1.5rem;
		margin-top: 1rem;
		border-radius: 0.25rem;
		text-decoration: none;
		font-weight: 600;
		transition: all 0.3s;
	}

	.btn-primary {
		background-color: var(--primary-color);
		color: var(--white);
	}

	.btn-primary:hover {
		background-color: var(--primary-dark);
	}

	@media (max-width: 576px) {
		.hero-small h1 {
			font-size: 2.25rem;
		}
	}
</style>
