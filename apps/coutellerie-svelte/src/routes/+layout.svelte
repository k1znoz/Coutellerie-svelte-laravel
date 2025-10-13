<script lang="ts">
	import '$lib/polyfills';
	import '../app.css';
	import { page } from '$app/stores';

	// import '$lib/styles/index.js';

	let navMenuActive = false;

	function toggleMobileMenu() {
		navMenuActive = !navMenuActive;
	}

	$: if ($page.url.pathname) {
		navMenuActive = false;
	}
</script>

<svelte:head>
	<title>Aloïs Sautet Coutellerie</title>
	<meta
		name="description"
		content="Artisanat traditionnel de coutellerie et développement web moderne"
	/>
	<link
		rel="stylesheet"
		href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
	/>
</svelte:head>

<header class="main-header">
	<nav class="main-nav">
		<a href="/" class="logo">
			<span class="logo-full">Aloïs Sautet Coutellerie</span>
			<span class="logo-short">
				<span>A.S</span>
				<span>Coutellerie</span>
			</span>
		</a>

		<button
			class="mobile-menu-toggle {navMenuActive ? 'active' : ''}"
			aria-label="Ouvrir le menu de navigation"
			on:click={toggleMobileMenu}
		>
			<i class="fas fa-bars"></i>
		</button>

		<ul class="nav-menu {navMenuActive ? 'active' : ''}">
			<li><a href="/">Accueil</a></li>
			<li><a href="/Gallery">Galerie</a></li>
			<li><a href="/About">À propos</a></li>
			<li><a href="/Contact">Contact</a></li>
		</ul>

		<div class="nav-cta">
			<a href="/Contact" class="cta-button">
				<span class="full-text">Demande de devis</span>
				<span class="short-text">Devis</span>
			</a>
		</div>
	</nav>
</header>

<main>
	<slot />
</main>

<footer class="main-footer">
	<div class="footer-section">
		<h3>Services</h3>
		<a href="/Gallery">Galerie</a>
		<a href="/About">À propos</a>
		<a href="/Contact">Contact</a>
	</div>
	<div class="footer-section">
		<h3>Légal</h3>
		<a href="/Legal">Mentions légales</a>
		<a href="/Legal#privacy">Politique de confidentialité</a>
	</div>
	<div class="footer-section">
		<h3>Social</h3>
		<div class="social-icons">
			<a href="https://facebook.com/" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
			<a href="https://instagram.com/" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
			<a href="https://linkedin.com/" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
		</div>
	</div>
</footer>

<style>
	/* Styles pour le header */
	.main-header {
		background-color: var(--white);
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		position: sticky;
		top: 0;
		z-index: 100;
	}

	.main-nav {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 1rem 2rem;
		max-width: 1200px;
		margin: 0 auto;
	}

	.logo {
		font-weight: bold;
		font-size: 1.5rem;
		text-decoration: none;
		color: var(--primary-color);
		display: flex;
		flex-direction: column;
	}

	.logo-full {
		display: block;
	}

	.logo-short {
		display: none;
	}

	@media (max-width: 768px) {
		.logo-full {
			display: none;
		}

		.logo-short {
			display: flex;
			flex-direction: column;
			align-items: center;
			line-height: 1.1;
			font-size: 1.2rem;
		}

		.logo-short span:first-child {
			font-size: 1.1em;
			font-weight: 700;
		}

		.logo-short span:last-child {
			font-size: 0.8em;
			font-weight: 600;
		}
	}

	.nav-menu {
		display: flex;
		list-style: none;
		margin: 0;
		padding: 0;
	}

	.nav-menu li {
		margin: 0 1rem;
	}

	.nav-menu a {
		text-decoration: none;
		color: var(--text-color);
		transition: color 0.3s;
	}

	.nav-menu a:hover {
		color: var(--primary-color);
	}

	.nav-cta .cta-button {
		background-color: var(--primary-color);
		color: white;
		padding: 0.5rem 1rem;
		border-radius: 0.25rem;
		text-decoration: none;
		transition: background-color 0.3s;
		display: flex;
		align-items: center;
	}

	.nav-cta .cta-button:hover {
		background-color: var(--primary-dark);
	}

	.mobile-menu-toggle {
		display: none;
		background: none;
		border: none;
		font-size: 1.5rem;
		cursor: pointer;
		color: var(--primary-color);
		padding: 0.5rem;
		border-radius: 0.25rem;
		transition: all 0.3s ease;
		position: relative;
		width: 40px;
		height: 40px;
		align-items: center;
		justify-content: center;
	}

	.mobile-menu-toggle:hover {
		background-color: var(--light-grey);
		color: var(--primary-dark);
	}

	.mobile-menu-toggle:focus {
		outline: 2px solid var(--primary-color);
		outline-offset: 2px;
	}

	/* Animation pour l'icône burger */
	.mobile-menu-toggle i {
		transition: transform 0.3s ease;
	}

	.mobile-menu-toggle.active i {
		transform: rotate(90deg);
	}

	/* Styles pour le footer */
	.main-footer {
		background-color: var(--dark);
		color: var(--white);
		padding: 2rem;
		display: flex;
		flex-wrap: wrap;
		justify-content: space-around;
	}

	.footer-section {
		margin: 1rem;
		display: flex;
		flex-direction: column;
		align-items: center;
		text-align: center;
		min-width: 200px;
	}

	.footer-section h3 {
		margin-bottom: 1rem;
		text-align: center;
	}

	.footer-section a {
		color: var(--light-grey);
		text-decoration: none;
		margin-bottom: 0.5rem;
		transition: color 0.3s;
		text-align: center;
		white-space: nowrap;
	}

	.footer-section a:hover {
		color: var(--white);
	}

	.social-icons {
		display: flex;
		gap: 1rem;
		justify-content: center;
	}

	.social-icons a {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 40px;
		height: 40px;
		border-radius: 50%;
		background-color: var(--primary-color);
		color: var(--white);
		transition: background-color 0.3s;
	}

	.social-icons a:hover {
		background-color: var(--primary-dark);
	}

	.cta-button .short-text {
		display: none;
	}

	/* Media queries pour responsive */
	@media (max-width: 768px) {
		.mobile-menu-toggle {
			display: flex;
		}

		.nav-menu {
			display: none;
			flex-direction: column;
			width: 100%;
			position: absolute;
			top: 100%;
			left: 0;
			background-color: var(--white);
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
			padding: 1rem;
			border-radius: 0 0 0.5rem 0.5rem;
			z-index: 1000;
		}

		.nav-menu.active {
			display: flex;
			animation: slideDown 0.3s ease-out;
		}

		.nav-menu li {
			margin: 0.5rem 0;
		}

		.nav-menu a {
			padding: 0.75rem 1rem;
			border-radius: 0.25rem;
			transition: background-color 0.3s;
		}

		.nav-menu a:hover {
			background-color: var(--light-grey);
		}

		.cta-button .full-text {
			display: none;
		}

		.cta-button .short-text {
			display: inline;
		}

		/* Responsive pour footer */
		.main-footer {
			flex-direction: column;
			align-items: center;
		}

		.footer-section {
			margin: 0.5rem 0;
			width: 100%;
		}

		.footer-section a {
			white-space: normal;
		}
	}

	/* Animation pour le menu déroulant */
	@keyframes slideDown {
		from {
			opacity: 0;
			transform: translateY(-10px);
		}
		to {
			opacity: 1;
			transform: translateY(0);
		}
	}
</style>
