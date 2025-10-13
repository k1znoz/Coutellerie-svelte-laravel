<script lang="ts">
	import HeroSection from '$lib/components/HeroSection.svelte';
	import { ValuesGrid } from '$lib/components/about';
	import type { Value, HeroPropsAbout } from '$lib/types';
	import { AboutSection } from '$lib/components/about';
	import { HammerAnvilAnimation } from '$lib/components/animations';
	import '$lib/styles/animations.css';
	import '$lib/styles/about.css';

	// Configuration de la section hero pour la page About
	const heroProps: HeroPropsAbout = {
		title: 'À propos de moi',
		subtitle: 'Artisan coutelier passionné par la forge et la coutellerie artisanale',
		backgroundImage: '/images/background/HeroAbout.webp',
		type: 'about'
	};

	// Données pour les valeurs
	const values: Value[] = [
		{
			icon: 'fas fa-hammer',
			title: 'Forge & Coutellerie',
			description:
				'Processus artisanal : du forgeage à la finition. Chaque lame est façonnée avec passion et précision.'
		},
		{
			icon: 'fas fa-fire',
			title: 'Travail du Métal',
			description:
				"Expertise dans la transformation de l'acier brut en outils tranchants d'exception. Du choix des matériaux au traitement thermique, chaque étape est maîtrisée."
		},
		{
			icon: 'fas fa-camera',
			title: 'Photographie',
			description:
				"Capture de l'essence du travail artisanal à travers l'objectif. Mise en valeur des créations et documentation du processus de fabrication."
		},
		{
			icon: 'fas fa-code',
			title: 'Développement Web',
			description:
				"Compétences numériques complémentaires pour valoriser l'artisanat traditionnel. Création de sites web pour partager la passion de la coutellerie."
		}
	];
</script>

<svelte:head>
	<title>À propos d'Aloïs - Artisan Coutelier & Forgeron</title>
</svelte:head>

<HeroSection props={heroProps} />

<!-- À propos de moi -->
<AboutSection imageUrl="/images/background/feu.webp" imageAlt="Atelier de coutellerie">
	<svelte:fragment slot="animation">
		<HammerAnvilAnimation
			width="80px"
			height="80px"
			autoPlay={true}
			interval={7000}
			showTitle={false}
		/>
	</svelte:fragment>
</AboutSection>

<ValuesGrid {values} title="Mon savoir-faire" />

<!-- Les styles ont été déplacés dans les composants individuels -->
