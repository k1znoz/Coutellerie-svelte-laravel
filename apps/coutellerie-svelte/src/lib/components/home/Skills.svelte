<script lang="ts">
	import { onMount } from 'svelte';

	// État d'animation
	let isVisible = false;
	let sectionElement: HTMLElement;

	// Données pour les compétences
	const skills = [
		{
			id: 'forge-coutellerie',
			category: 'Art de la Forge & Coutellerie',
			items: [
				{ id: 'forge-charbon', text: 'Forge traditionnelle au charbon' },
				{ id: 'trempe-revenu', text: 'Maîtrise de la trempe et du revenu' },
				{ id: 'polissage', text: 'Finitions et polissage à la main' }
			]
		},
		{
			id: 'artisanat',
			category: 'Savoir-faire Artisanal',
			items: [
				{ id: 'bois-nobles', text: 'Travail des bois nobles (ébène, palissandre)' },
				{ id: 'etuis', text: "Création d'étuis en cuir" },
				{ id: 'affutage', text: 'Affûtage professionnel sur pierre' }
			]
		},
		{
			id: 'autres',
			category: 'Autres Compétences',
			items: [{ id: 'dev-web', text: 'Développement web (activité secondaire)' }]
		}
	];

	onMount(() => {
		const observer = new IntersectionObserver(
			(entries) => {
				entries.forEach((entry) => {
					if (entry.isIntersecting) {
						isVisible = true;
						observer.unobserve(entry.target);
					}
				});
			},
			{
				threshold: 0.2,
				rootMargin: '0px 0px -50px 0px'
			}
		);

		if (sectionElement) {
			observer.observe(sectionElement);
		}

		return () => observer.disconnect();
	});
</script>

<!-- Compétences -->
<section bind:this={sectionElement} class:animate={isVisible}>
	<div class="container">
		<h2>Mes Compétences</h2>
		<!-- Compétences principales -->
		<div class="main-skills">
			{#each skills.slice(0, 2) as skillSet (skillSet.id)}
				<div class="card primary-skill">
					<div class="card-header">
						<div class="skill-icon">
							{#if skillSet.id === 'forge-coutellerie'}
								<i class="fas fa-fire"></i>
							{:else if skillSet.id === 'artisanat'}
								<i class="fas fa-tools"></i>
							{/if}
						</div>
					</div>
					<div class="card-body">
						<h3 class="card-title">{skillSet.category}</h3>
						<ul class="list-disc">
							{#each skillSet.items as item (item.id)}
								<li>{item.text}</li>
							{/each}
						</ul>
					</div>
				</div>
			{/each}
		</div>

		<!-- Compétence secondaire -->
		<div class="secondary-skills">
			{#each skills.slice(2) as skillSet (skillSet.id)}
				<div class="card secondary-skill">
					<div class="card-header">
						<div class="skill-icon">
							<i class="fas fa-laptop-code"></i>
						</div>
					</div>
					<div class="card-body">
						<h3 class="card-title">{skillSet.category}</h3>
						<ul class="list-disc">
							{#each skillSet.items as item (item.id)}
								<li>{item.text}</li>
							{/each}
						</ul>
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

	.main-skills {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 2rem;
		margin-bottom: 3rem;
	}

	/* États initiaux des cartes */
	.card {
		opacity: 0;
		transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
		background: var(--white);
		border-radius: 0.75rem;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
		overflow: hidden;
		position: relative;
	}

	/* Animations déclenchées par la classe .animate */
	.animate .main-skills .card:nth-child(1) {
		opacity: 1;
		transform: translateX(0);
		transition-delay: 0.1s;
	}

	.animate .main-skills .card:nth-child(2) {
		opacity: 1;
		transform: translateX(0);
		transition-delay: 0.3s;
	}

	.animate .secondary-skills .card {
		opacity: 1;
		transform: translateY(0) scale(0.95);
		transition-delay: 0.5s;
	}

	/* États initiaux spécifiques */
	.main-skills .card:nth-child(1) {
		transform: translateX(-30px);
	}

	.main-skills .card:nth-child(2) {
		transform: translateX(30px);
	}

	.secondary-skills {
		display: flex;
		justify-content: center;
	}

	.secondary-skill {
		max-width: 400px;
		width: 100%;
		transform: translateY(30px) scale(0.95);
	}

	/* Transitions séparées pour les interactions de survol */
	.animate .card {
		transition:
			transform 0.4s cubic-bezier(0.4, 0, 0.2, 1),
			box-shadow 0.4s cubic-bezier(0.4, 0, 0.2, 1),
			opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1);
	}

	.card::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 3px;
		background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
		transform: scaleX(0);
		transition: transform 0.3s ease;
	}

	.card:hover::before {
		transform: scaleX(1);
	}

	.card-header {
		padding: 1rem 1.5rem 0;
		display: flex;
		justify-content: flex-end;
	}

	.skill-icon {
		width: 50px;
		height: 50px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
		color: white;
		font-size: 1.2rem;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
		transition: transform 0.3s ease;
	}

	.card:hover .skill-icon {
		transform: scale(1.1) rotate(5deg);
	}

	.primary-skill {
		border-left: 4px solid var(--primary-color);
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
		background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
	}

	.primary-skill:hover {
		box-shadow: 0 12px 28px rgba(0, 0, 0, 0.2);
		transform: translateY(-10px) scale(1.02);
	}

	.primary-skill .card-body {
		background: rgba(255, 255, 255, 0.9);
		backdrop-filter: blur(10px);
	}

	.secondary-skill {
		border-left: 4px solid var(--secondary-color);
		background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
	}

	.animate .secondary-skill:hover {
		box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
		transform: scale(1) translateY(-5px);
	}

	.secondary-skill .skill-icon {
		background: linear-gradient(135deg, var(--secondary-color), #6c757d);
	}

	.card-body {
		padding: 1.5rem;
	}

	.card-title {
		font-size: 1.35rem;
		margin-bottom: 1rem;
		color: var(--primary-color);
		font-weight: 600;
		text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
	}

	.list-disc {
		list-style: none;
		padding-left: 0;
	}

	.list-disc li {
		margin-bottom: 0.75rem;
		padding-left: 1.5rem;
		position: relative;
		transition: all 0.2s ease;
	}

	.list-disc li::before {
		content: '\2022';
		position: absolute;
		left: 0;
		color: var(--primary-color);
		font-weight: bold;
		font-size: 1.2rem;
		transition: transform 0.2s ease;
	}

	.card:hover .list-disc li::before {
		transform: scale(1.3);
	}

	.list-disc li:hover {
		transform: translateX(5px);
		color: var(--primary-color);
	}

	@media (max-width: 768px) {
		.main-skills {
			grid-template-columns: 1fr;
			gap: 1.5rem;
			margin-bottom: 2rem;
		}

		.secondary-skill {
			max-width: none;
			transform: translateY(30px) scale(1);
		}

		.animate .secondary-skills .card {
			transform: translateY(0) scale(1);
		}

		.animate .secondary-skill:hover {
			transform: translateY(-5px) scale(1);
		}
	}

	@media (max-width: 576px) {
		section {
			padding: 2rem 1rem;
		}
	}
</style>
