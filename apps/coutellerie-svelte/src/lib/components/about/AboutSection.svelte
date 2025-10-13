<script lang="ts">
	export let imageUrl: string = '/images/background/feu.webp';
	export let imageAlt: string = 'Atelier de coutellerie';
	export const showAnimation: boolean = true;
	import '$lib/styles/about.css';
</script>

<section class="about-section">
	<div class="container">
		<div class="about-grid">
			<div class="about-text">
				<h2>Mon histoire</h2>
				<p>
					Passionné depuis toujours par le travail manuel et la création, j'ai découvert la forge et
					par la coutellerie artisanale au travers des fêtes médieval de Provins (77) .
				</p>
				<p>
					J'apprends et me perfectionne depuis 2016 dans cet art exigeant. Autodidacte, je continue
					à développer mes techniques de forge et de coutellerie avec passion et persévérance.
				</p>
				<p>
					Chaque couteau est fabriqué à la main dans mon atelier, avec un souci constant de qualité
					et d'authenticité.
				</p>
				<div class="forge-animation">
					<slot name="animation">
						<!-- Par défaut, pas d'animation si aucun slot n'est fourni -->
					</slot>
				</div>
			</div>
			<div class="about-image">
				<img src={imageUrl} alt={imageAlt} loading="lazy" />
			</div>
		</div>
	</div>
</section>

<style>
	/* Les styles ont été déplacés vers about.css */
	.container {
		max-width: 1200px;
		margin: 0 auto;
	}

	h2 {
		text-align: center;
		margin-bottom: 2rem;
		font-size: 2rem;
	}

	.about-text p {
		margin-bottom: 1rem;
		line-height: 1.6;
	}

	.forge-animation {
		margin-top: 2rem;
		text-align: center;
		padding: 1rem;
		border: 1px dashed var(--primary-color);
		border-radius: 0.5rem;
		background-color: rgba(var(--primary-rgb), 0.05);
	}

	.about-image img {
		width: 100%;
		border-radius: 0.5rem;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
	}
</style>
