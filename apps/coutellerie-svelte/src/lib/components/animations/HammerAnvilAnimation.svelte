<script lang="ts">
	import { onMount } from 'svelte';
	import '$lib/styles/animations.css';

	// Propriétés optionnelles pour personnaliser l'animation
	export let width = '75px';
	export let height = '75px';
	export let autoPlay = false;
	export let interval = 4000; // Interval en millisecondes si autoPlay est true
	export let showTitle = true; // Afficher ou non le titre de l'animation

	// Références aux éléments DOM avec des types explicites
	let hammer: HTMLImageElement;
	let container: HTMLDivElement;
	let isAnimating = false;
	// Fonction pour jouer l'animation avec protection contre le double-déclenchement
	function playAnimation() {
		if (hammer && !isAnimating) {
			isAnimating = true;

			// Réinitialiser l'état de l'animation
			hammer.classList.remove('strike');

			// Force un reflow pour s'assurer que l'animation se réinitialise
			void hammer.offsetWidth; // Définir la fonction de nettoyage avant d'ajouter la classe
			const handleAnimationEnd = () => {
				if (hammer) {
					hammer.classList.remove('strike');
					isAnimating = false;
				}
			};

			// Ajouter l'écouteur d'événement avant de démarrer l'animation
			hammer.addEventListener('animationend', handleAnimationEnd, { once: true });

			// Ajouter la classe d'animation
			hammer.classList.add('strike');

			// Délai de secours pour s'assurer que l'animation se termine
			const timeoutId = setTimeout(() => {
				if (isAnimating) {
					handleAnimationEnd();
				}
			}, 1900);

			// Nettoyer le timeout si le composant est détruit
			return () => {
				clearTimeout(timeoutId);
				if (hammer) {
					hammer.removeEventListener('animationend', handleAnimationEnd);
				}
			};
		}
	}

	onMount(() => {
		// Joue l'animation une fois au chargement pour s'assurer qu'elle fonctionne
		if (autoPlay) {
			// Petit délai pour s'assurer que le DOM est prêt
			setTimeout(playAnimation, 1300);

			if (interval > 0) {
				// Animation automatique à intervalles réguliers
				const timer = setInterval(playAnimation, interval);

				// Nettoyer le timer quand le composant est détruit
				return () => clearInterval(timer);
			}
		}
	});
</script>

<div
	class="anvil-container"
	style="width: {width}; height: {height};"
	on:click={playAnimation}
	bind:this={container}
	role="button"
	tabindex="0"
	on:keydown={(e) => e.key === 'Enter' && playAnimation()}
	aria-label="Animer le marteau et l'enclume"
>
	<!-- Ajout d'un titre pour la boîte à outils (optionnel) -->
	{#if showTitle}
		<div class="animation-title">Forge traditionnelle</div>
	{/if}
	<!-- Les SVG pour l'animation -->
	<div class="animation-content" class:no-title={!showTitle}>
		<!-- Conteneur d'isolation pour l'animation -->
		<div class="animation-isolation">
			<!-- Le marteau doit être une image SVG simple pour l'animation -->
			<img class="hammer" src="/images/animations/Hammer.svg" alt="Marteau" bind:this={hammer} />

			<!-- L'enclume est statique -->
			<img class="anvil" src="/images/animations/Anvil.svg" alt="Enclume" />
		</div>
	</div>
</div>

<style>
	.anvil-container {
		position: relative;
		display: inline-block;
		cursor: pointer;
		user-select: none;
		overflow: visible;
		text-align: center;
		padding: 10px;
		background-color: rgba(0, 0, 0, 0.02);
		border-radius: 8px;
		border: 1px solid #eaeaea;
		transition:
			transform 0.2s,
			box-shadow 0.2s;
	}

	/* Ajouter un indice visuel pour indiquer que c'est interactif */
	.anvil-container::after {
		content: 'Cliquez!';
		font-size: 10px;
		color: var(--primary-color, #666);
		opacity: 0;
		position: absolute;
		bottom: -5px;
		left: 50%;
		background: rgba(255, 255, 255, 0.9);
		padding: 2px 6px;
		border-radius: 10px;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
		transition: opacity 0.3s;
	}
	.anvil-container:hover {
		transform: translateY(-2px);
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	}

	.anvil-container:hover::after {
		opacity: 1;
	}

	.animation-title {
		font-size: 14px;
		font-weight: 600;
		margin-bottom: 10px;
		color: var(--primary-color, #333);
	}
	.animation-content {
		position: relative;
		height: calc(100% - 25px);
		min-height: 60px;
		width: 75px; /* Largeur fixe pour correspondre au conteneur original */
	}

	.animation-content.no-title {
		height: 100%;
		right: 17%;
		min-height: 100%;
	}
	.hammer {
		position: absolute;
		width: 40px;
		height: auto;
		top: 2px;
		left: 30px;
		transform-origin: bottom right; /* Point de pivot en bas à droite */
		z-index: 2;
		transform: rotate(30deg); /*  Position initiale explicite */
	}

	.anvil {
		display: block;
		width: 50px;
		height: auto;
		position: absolute;
		bottom: 0;
		left: 6.5px;
		z-index: 1;
	}
</style>
