<script>
	// Pas de props, image statique pour l'instant
</script>

<section class="map-section">
	<div class="container">
		<h2>Localisation</h2>
		<div class="map-container">
			<!-- Utiliser une image qui existe ou afficher un message -->
			<div class="map-placeholder">
				<p>Carte de localisation à venir prochainement</p>
			</div>
		</div>
	</div>
</section>

<style>
	.map-section {
		background-color: var(--light-grey);
	}

	.map-section h2 {
		text-align: center;
	}

	.map-container {
		height: 400px;
		border-radius: 0.5rem;
		overflow: hidden;
	}

	.map-placeholder {
		display: flex;
		align-items: center;
		justify-content: center;
		height: 100%;
		background-color: #f5f5f5;
		border-radius: 0.5rem;
	}

	.map-placeholder p {
		font-size: 1.25rem;
		color: #666;
	}

	@media (max-width: 992px) {
		.map-container {
			height: 300px;
		}
	}
</style>
