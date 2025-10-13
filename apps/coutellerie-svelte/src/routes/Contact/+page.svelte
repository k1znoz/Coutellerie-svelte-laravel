<script lang="ts">
	import { onMount } from 'svelte';
	import { ContactForm, ContactInfo, MapSection, SocialMediaLinks } from '$lib/components/Contact';
	import HeroSection from '$lib/components/HeroSection.svelte';

	import type { HeroPropsContact } from '$lib/types';

	// Configuration de la section hero pour la page Contact
	const heroProps: HeroPropsContact = {
		title: 'Contact',
		subtitle: "N'hésitez pas à me contacter pour toute demande",
		backgroundImage: '/images/background/max.webp',
		type: 'contact'
	};

	onMount(() => {
		// Initialisation de la carte si nécessaire
	});
</script>

<svelte:head>
	<title>Contact - Coutellerie Artisanale</title>
	<meta
		name="description"
		content="Contactez la Coutellerie Artisanale pour toute question ou demande personnalisée."
	/>
	<!-- Ajoutez ici d'éventuelles librairies pour la carte si besoin -->
</svelte:head>

<HeroSection props={heroProps} />

<section class="contact-section">
	<div class="container">
		<div class="contact-grid">
			<div>
				<ContactInfo />
				<SocialMediaLinks />
			</div>
			<ContactForm />
		</div>
	</div>
</section>

<MapSection />

<style>
	section {
		padding: 4rem 2rem;
	}

	.container {
		max-width: 1200px;
		margin: 0 auto;
	}

	.contact-grid {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 3rem;
	}

	@media (max-width: 992px) {
		.contact-grid {
			grid-template-columns: 1fr;
			gap: 2rem;
		}
	}

	@media (max-width: 576px) {
		section {
			padding: 3rem 1rem;
		}
	}
</style>
