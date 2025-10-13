<script>
	// Pas de props pour l'instant, données statiques
</script>

<div class="contact-info">
	<h2>Mes coordonnées</h2>
	<div class="info-item">
		<i class="fas fa-map-marker-alt"></i>
		<div>
			<h3>Adresse</h3>
			<p>Voutezac 19130, France</p>
		</div>
	</div>
	<div class="info-item">
		<i class="fas fa-phone-alt"></i>
		<div>
			<h3>Téléphone</h3>
			<p>+33 6 38 67 58 86</p>
		</div>
	</div>
	<div class="info-item">
		<i class="fas fa-envelope"></i>
		<div>
			<h3>Email</h3>
			<p>alois.sautet@gmail.com</p>
		</div>
	</div>
	<div class="info-item">
		<i class="fas fa-clock"></i>
		<div>
			<h3>Heures d'ouverture</h3>
			<p>Sur rendez-vous</p>
		</div>
	</div>
</div>

<style>
	.contact-info h2 {
		margin-bottom: 2rem;
		font-size: 2rem;
	}

	.info-item {
		display: flex;
		margin-bottom: 2rem;
	}

	.info-item i {
		font-size: 1.5rem;
		color: var(--primary-color);
		margin-right: 1rem;
		width: 2.5rem;
		height: 2.5rem;
		border-radius: 50%;
		background-color: var(--light-grey);
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.info-item h3 {
		font-size: 1.2rem;
		margin-bottom: 0.5rem;
		color: var(--primary-color);
	}

	.info-item p {
		margin-bottom: 0.25rem;
		color: var(--text-color);
	}
</style>
