<script lang="ts">
	interface LightboxItem {
		id: string | number;
		title: string;
		category: string;
		images: string[];
		description: string;
	}

	export let isOpen = false;
	export let item: LightboxItem | null = null;
	export let onClose: () => void = () => {};

	let currentImageIndex = 0;
	let isFullscreenMode = false;

	// Réinitialiser l'index quand l'item change
	$: if (item) {
		currentImageIndex = 0;
		isFullscreenMode = false;
	}

	function close() {
		onClose();
	}

	function previousImage() {
		if (item && item.images.length > 1) {
			currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : item.images.length - 1;
		}
	}

	function nextImage() {
		if (item && item.images.length > 1) {
			currentImageIndex = currentImageIndex < item.images.length - 1 ? currentImageIndex + 1 : 0;
		}
	}

	function selectImage(index: number) {
		currentImageIndex = index;
	}

	function toggleFullscreen() {
		isFullscreenMode = !isFullscreenMode;
	}

	function trapFocus() {
		const lightbox = document.querySelector('.lightbox');
		if (!lightbox) return;

		const focusableElements = lightbox.querySelectorAll(
			'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
		);
		const firstElement = focusableElements[0];
		const lastElement = focusableElements[focusableElements.length - 1];

		function handleFocusTrap(e: Event) {
			const keyEvent = e as KeyboardEvent;
			if (keyEvent.key !== 'Tab') return;

			if (keyEvent.shiftKey && document.activeElement === firstElement) {
				keyEvent.preventDefault();
				(lastElement as HTMLElement).focus();
			} else if (!keyEvent.shiftKey && document.activeElement === lastElement) {
				keyEvent.preventDefault();
				(firstElement as HTMLElement).focus();
			}
		}

		lightbox.addEventListener('keydown', handleFocusTrap);

		// Focus the first element when lightbox opens
		(firstElement as HTMLElement)?.focus();

		// Cleanup event listener when lightbox closes
		const observer = new MutationObserver(() => {
			if (!isOpen) {
				lightbox.removeEventListener('keydown', handleFocusTrap);
				observer.disconnect();
			}
		});
		observer.observe(document.body, { attributes: true, childList: true, subtree: true });
	}

	$: if (isOpen && item) {
		setTimeout(() => trapFocus(), 0);
	}
</script>

{#if isOpen && item}
	<div
		class="lightbox"
		on:click={close}
		on:keydown={(e) => e.key === 'Escape' && close()}
		role="dialog"
		aria-modal="true"
		aria-labelledby="lightbox-title"
		tabindex="-1"
	>
		<button
			class="lightbox-close"
			on:click|stopPropagation={close}
			aria-label="Fermer la lightbox"
			type="button">&times;</button
		>

		<div
			class="lightbox-content {isFullscreenMode ? 'fullscreen' : ''}"
			role="presentation"
			on:click|stopPropagation={() => {}}
		>
			<div class="image-container">
				<img src={item.images[currentImageIndex]} alt={item.title} />

				<!-- Bouton plein écran -->
				<button
					class="fullscreen-button"
					on:click|stopPropagation={toggleFullscreen}
					aria-label={isFullscreenMode ? 'Quitter le plein écran' : 'Voir en plein écran'}
					title={isFullscreenMode ? 'Quitter le plein écran' : 'Voir en plein écran'}
				>
					{isFullscreenMode ? '⤓' : '⤢'}
				</button>

				<!-- Navigation arrows -->
				{#if item.images.length > 1}
					<button
						class="nav-button nav-prev"
						on:click|stopPropagation={previousImage}
						aria-label="Image précédente"
					>
						‹
					</button>
					<button
						class="nav-button nav-next"
						on:click|stopPropagation={nextImage}
						aria-label="Image suivante"
					>
						›
					</button>
				{/if}

				<!-- Image thumbnails -->
				{#if item.images.length > 1}
					<div class="thumbnail-container">
						{#each item.images as image, index}
							<button
								class="thumbnail {currentImageIndex === index ? 'active' : ''}"
								on:click|stopPropagation={() => selectImage(index)}
								aria-label="Voir l'image {index + 1}"
							>
								<img src={image} alt="{item.title} - Image {index + 1}" loading="lazy" />
							</button>
						{/each}
					</div>
				{/if}
			</div>

			<div class="lightbox-info">
				<h3 id="lightbox-title">{item.title}</h3>
				<p class="category">{item.category}</p>
				<p>{item.description}</p>
				{#if item.images.length > 1}
					<p class="image-counter">Image {currentImageIndex + 1} sur {item.images.length}</p>
				{/if}
				<a href="/Contact" class="btn btn-primary">Demander un devis similaire</a>
			</div>
		</div>
	</div>
{/if}

<style>
	.lightbox {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, 0.9);
		display: flex;
		justify-content: center;
		align-items: center;
		z-index: 1000;
		padding: 2rem;
	}

	.lightbox-close {
		position: absolute;
		top: 20px;
		right: 30px;
		font-size: 3rem;
		color: var(--white);
		cursor: pointer;
		z-index: 1001;
		background: none;
		border: none;
	}

	.lightbox-content {
		display: flex;
		width: 90%;
		max-width: 1200px;
		height: auto;
		max-height: 80vh;
		background-color: var(--white);
		border-radius: 0.5rem;
		overflow: hidden;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
	}

	.image-container {
		width: 60%;
		position: relative;
		display: flex;
		flex-direction: column;
		background-color: #f8f9fa;
		align-items: center;
		justify-content: center;
		min-height: 400px;
	}

	.image-container img {
		max-width: 100%;
		max-height: 60vh;
		object-fit: contain;
		object-position: center;
		width: auto;
		height: auto;
		border-radius: 4px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
	}

	.lightbox-info {
		width: 40%;
		padding: 2rem;
		display: flex;
		flex-direction: column;
		overflow-y: auto;
		max-height: 80vh;
	}

	.lightbox-info h3 {
		color: var(--primary-color);
		margin-bottom: 0.5rem;
		font-size: 1.5rem;
	}

	.lightbox-info .category {
		margin-bottom: 1rem;
		font-style: italic;
		color: #666;
	}

	.lightbox-info p {
		margin-bottom: 1.5rem;
		line-height: 1.6;
	}

	.lightbox-info .btn {
		margin-top: auto;
		align-self: flex-start;
	}

	.nav-button {
		position: absolute;
		top: 50%;
		transform: translateY(-50%);
		background: rgba(0, 0, 0, 0.7);
		color: white;
		border: none;
		padding: 1rem;
		cursor: pointer;
		font-size: 2rem;
		z-index: 10;
		transition: background-color 0.3s;
	}

	.nav-button:hover {
		background: rgba(0, 0, 0, 0.9);
	}

	.nav-prev {
		left: 10px;
	}

	.nav-next {
		right: 10px;
	}

	.thumbnail-container {
		display: flex;
		gap: 0.5rem;
		padding: 1rem;
		background: rgba(0, 0, 0, 0.1);
		overflow-x: auto;
		max-height: 120px;
	}

	.thumbnail {
		flex-shrink: 0;
		width: 80px;
		height: 60px;
		border: 2px solid transparent;
		cursor: pointer;
		background: none;
		padding: 0;
		overflow: hidden;
		border-radius: 4px;
	}

	.thumbnail.active {
		border-color: var(--primary-color, #007bff);
	}

	.thumbnail img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}

	.image-counter {
		font-size: 0.9rem;
		color: #666;
		margin-bottom: 1rem;
	}

	.fullscreen-button {
		position: absolute;
		top: 15px;
		right: 15px;
		background: rgba(0, 0, 0, 0.7);
		color: white;
		border: none;
		padding: 0.5rem;
		cursor: pointer;
		font-size: 1.5rem;
		z-index: 10;
		border-radius: 4px;
		transition: background-color 0.3s;
	}

	.fullscreen-button:hover {
		background: rgba(0, 0, 0, 0.9);
	}

	.lightbox-content.fullscreen {
		width: 95vw;
		height: 95vh;
		max-width: none;
		max-height: none;
	}

	.lightbox-content.fullscreen .image-container {
		width: 100%;
		min-height: 70vh;
	}

	.lightbox-content.fullscreen .image-container img {
		max-height: 85vh;
		max-width: 95%;
	}

	.lightbox-content.fullscreen .lightbox-info {
		position: absolute;
		bottom: 0;
		right: 0;
		width: 350px;
		height: 200px;
		background: rgba(255, 255, 255, 0.95);
		backdrop-filter: blur(10px);
		border-radius: 8px 0 0 0;
		padding: 1rem;
		overflow-y: auto;
	}

	.lightbox-content.fullscreen .thumbnail-container {
		position: absolute;
		bottom: 10px;
		left: 50%;
		transform: translateX(-50%);
		background: rgba(0, 0, 0, 0.8);
		border-radius: 25px;
		padding: 0.5rem 1rem;
		width: auto;
		max-width: 80%;
	}

	@media (max-width: 992px) {
		.lightbox-content {
			flex-direction: column;
			width: 95%;
			max-width: 600px;
			max-height: 90vh;
		}

		.image-container {
			width: 100%;
			min-height: 300px;
		}

		.image-container img {
			max-height: 50vh;
			max-width: 90%;
		}

		.lightbox-info {
			width: 100%;
			max-height: 40vh;
		}

		.nav-button {
			padding: 0.5rem;
			font-size: 1.5rem;
		}

		.thumbnail-container {
			padding: 0.5rem;
			max-height: 100px;
		}

		.thumbnail {
			width: 60px;
			height: 45px;
		}

		.lightbox-content.fullscreen .lightbox-info {
			position: relative;
			width: 100%;
			height: auto;
			background: white;
			backdrop-filter: none;
			border-radius: 0;
			max-height: 30vh;
		}

		.lightbox-content.fullscreen .thumbnail-container {
			position: relative;
			bottom: auto;
			left: auto;
			transform: none;
			width: 100%;
			max-width: 100%;
			border-radius: 0;
			background: rgba(0, 0, 0, 0.1);
		}

		.fullscreen-button {
			top: 10px;
			right: 10px;
			padding: 0.3rem;
			font-size: 1.2rem;
		}
	}

	@media (max-width: 576px) {
		.lightbox {
			padding: 1rem;
		}

		.lightbox-close {
			top: 10px;
			right: 15px;
			font-size: 2rem;
		}

		.lightbox-content {
			max-height: 95vh;
		}

		.lightbox-info {
			padding: 1rem;
		}
	}
</style>
