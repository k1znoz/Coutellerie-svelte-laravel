<script lang="ts">
	import { HammerAnvilAnimation } from '$lib/components/animations';

	// Données pour le processus de fabrication
	const craftProcess = [
		{
			id: 'conception',
			icon: 'fa-drafting-compass',
			title: 'Conception',
			description: 'Création de designs uniques adaptés aux besoins spécifiques.'
		},
		{
			id: 'forge',
			title: 'Forge',
			description: 'Travail de la lame selon les techniques traditionnelles.',
			hasAnimation: true
		},
		{
			id: 'montage',
			icon: 'fa-wrench',
			title: 'Montage',
			description: 'Assemblage des différentes parties du couteau.'
		},
		{
			id: 'finition',
			icon: 'fa-toolbox',
			title: 'Finition',
			description: 'Polissage et assemblage minutieux pour un résultat parfait.'
		}
	];
</script>

<!-- Processus de fabrication -->
<section id="process">
	<div class="container">
		<h2>Processus de Fabrication</h2>
		<div class="grid grid-cols-4">
			{#each craftProcess as step (step.id)}
				<div class="card">
					<div class="card-body text-center">
						{#if step.hasAnimation}
							<div class="animation-container">
								<HammerAnvilAnimation
									width="80px"
									height="80px"
									autoPlay={true}
									interval={7000}
									showTitle={false}
								/>
							</div>
						{:else}
							<i class="fas {step.icon} mb-4"></i>
						{/if}
						<h3 class="card-title">{step.title}</h3>
						<p>{step.description}</p>
					</div>
				</div>
			{/each}
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

	.grid {
		display: grid;
		gap: 1.5rem;
		margin-bottom: 2rem;
	}

	.grid-cols-4 {
		grid-template-columns: repeat(4, 1fr);
	}

	.card {
		background: var(--white);
		border-radius: 0.5rem;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
		overflow: hidden;
		transition:
			transform 0.3s,
			box-shadow 0.3s;
	}

	.card:hover {
		box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
		transform: translateY(-5px);
	}

	.card-body {
		padding: 1.5rem;
	}

	.card-title {
		font-size: 1.25rem;
		margin-bottom: 0.5rem;
		color: var(--primary-color);
	}

	i.mb-4 {
		font-size: 2rem;
		color: var(--primary-color);
	}

	@media (max-width: 992px) {
		.grid-cols-4 {
			grid-template-columns: repeat(2, 1fr);
		}
	}

	@media (max-width: 576px) {
		.grid-cols-4 {
			grid-template-columns: 1fr;
		}

		section {
			padding: 2rem 1rem;
		}
	}
</style>
