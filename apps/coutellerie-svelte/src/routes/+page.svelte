<script lang="ts">
	import HeroSection from '$lib/components/HeroSection.svelte';
	import GalleryTeaser from '$lib/components/home/GalleryTeaser.svelte';
	import CraftProcess from '$lib/components/home/CraftProcess.svelte';
	import Skills from '$lib/components/home/Skills.svelte';
	import '$lib/styles/heroSection.css';
	import '$lib/styles/animations.css';

	// Données pour le hero principal
	const heroProps = {
		title: 'Artisan Forgeron Coutelier',
		subtitle: 'Découvrez un savoir-faire artisanal où chaque lame est façonnée avec passion.',
		backgroundImage: '/images/background/hero-bg.webp',
		type: 'home',
		ctaText: 'Découvrir mon travail',
		ctaLink: '/Gallery'
	};
</script>

<svelte:head>
	<title>Accueil - Coutellerie Artisanale & Développement Web</title>
	<script src="https://static.elfsight.com/platform/platform.js" async></script>
</svelte:head>

<!-- Hero Section avec CTA -->
<HeroSection props={{ ...heroProps, type: 'home' }} />

<!-- Teaser Galerie -->
<GalleryTeaser />

<!-- Processus de fabrication -->
<CraftProcess />

<!-- Compétences -->
<Skills />

<!-- Instagram Feed -->
<div>
	<div class="elfsight-app-05694363-406c-428f-ae94-e53e84181fa4" data-elfsight-app-lazy></div>
</div>

<!-- Call to Action -->
<section class="cta-section">
	<div class="container text-center">
		<h2>Intéressé par mes créations ?</h2>
		<p class="mb-4">Découvrez mes réalisations ou contactez-moi pour discuter de votre projet.</p>
		<div>
			<a href="/Gallery" class="btn btn-secondary">Voir la galerie</a>
			<a href="/Contact" class="btn btn-outline">Me contacter</a>
		</div>
	</div>
</section>

<style>
	section {
		padding: 4rem 2rem;
	}

	h2 {
		text-align: center;
		margin-bottom: 2rem;
		font-size: 2rem;
	}

	.btn-secondary {
		background-color: var(--secondary-color);
		color: var(--white);
	}

	.btn-secondary:hover {
		background-color: var(--secondary-dark);
	}

	.btn-outline {
		background-color: transparent;
		color: var(--white);
		border: 2px solid var(--white);
	}

	.btn-outline:hover {
		background-color: var(--white);
		color: var(--accent-color);
	}

	.cta-section {
		background-color: var(--accent-color);
		color: var(--white);
	}

	@media (max-width: 576px) {
		section {
			padding: 2rem 1rem;
		}
	}
</style>
