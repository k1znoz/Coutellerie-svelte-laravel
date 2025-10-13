<script>
	// Données pour les mentions légales
	const legalInfo = {
		business: {
			name: 'Aloïs Sautet Coutellerie',
			status: 'Entreprise individuelle',
			address: 'Voutezac 19130, France',
			email: 'alois.sautet@gmail.com',
			phone: '+33 6 38 67 58 86',
			siret: '123 456 789 00012',
			rcs: 'RCS Clermont-Ferrand'
		},
		webmaster: {
			name: 'Aloïs Sautet',
			email: 'alois.sautet@gmail.com'
		},
		hosting: {
			name: 'OVH SAS',
			address: '2 rue Kellermann - 59100 Roubaix - France',
			phone: '1007'
		}
	};
</script>

<svelte:head>
	<title>Mentions légales - Coutellerie Artisanale</title>
</svelte:head>

<section class="legal-header">
	<div class="container">
		<h1>Mentions légales</h1>
		<p>Dernière mise à jour : 06/05/2025</p>
	</div>
</section>

<section class="legal-content">
	<div class="container">
		<div class="legal-section">
			<h2 id="editor">Éditeur du site</h2>
			<p>
				Le site <strong>Aloïs Sautet Coutellerie</strong> est édité par :
			</p>
			<ul>
				<li><strong>Nom de l'entreprise :</strong> {legalInfo.business.name}</li>
				<li><strong>Statut juridique :</strong> {legalInfo.business.status}</li>
				<li><strong>Adresse :</strong> {legalInfo.business.address}</li>
				<li><strong>Email :</strong> {legalInfo.business.email}</li>
				<li><strong>Téléphone :</strong> {legalInfo.business.phone}</li>
				<li><strong>SIRET :</strong> {legalInfo.business.siret}</li>
				<li><strong>RCS :</strong> {legalInfo.business.rcs}</li>
			</ul>
			<p>Directeur de la publication : Aloïs Sautet</p>
		</div>

		<div class="legal-section">
			<h2 id="webmaster">Webmaster</h2>
			<p>Le site a été créé et est maintenu par :</p>
			<ul>
				<li><strong>Nom :</strong> {legalInfo.webmaster.name}</li>
				<li><strong>Email :</strong> {legalInfo.webmaster.email}</li>
			</ul>
		</div>

		<div class="legal-section">
			<h2 id="hosting">Hébergement</h2>
			<p>Ce site est hébergé par :</p>
			<ul>
				<li><strong>Société :</strong> {legalInfo.hosting.name}</li>
				<li><strong>Adresse :</strong> {legalInfo.hosting.address}</li>
				<li><strong>Téléphone :</strong> {legalInfo.hosting.phone}</li>
			</ul>
		</div>

		<div class="legal-section">
			<h2 id="intellectual-property">Propriété intellectuelle</h2>
			<p>
				L'ensemble de ce site relève de la législation française et internationale sur le droit
				d'auteur et la propriété intellectuelle. Tous les droits de reproduction sont réservés, y
				compris pour les documents téléchargeables et les représentations iconographiques et
				photographiques.
			</p>
			<p>
				Toute reproduction totale ou partielle de ce site est strictement interdite sans
				autorisation expresse de l'éditeur.
			</p>
		</div>

		<div class="legal-section">
			<h2 id="privacy">Politique de confidentialité</h2>
			<h3>Données personnelles</h3>
			<p>
				Conformément au Règlement Général sur la Protection des Données (RGPD), vous disposez d'un
				droit d'accès, de rectification, d'opposition et de suppression des données vous concernant.
				Vous pouvez exercer ce droit en nous contactant par email à {legalInfo.business.email}.
			</p>

			<h3>Cookies</h3>
			<p>
				Ce site utilise des cookies pour améliorer l'expérience utilisateur. En naviguant sur ce
				site, vous acceptez l'utilisation de ces cookies.
			</p>
			<p>
				Les cookies sont de petits fichiers texte stockés sur votre ordinateur. Ils nous permettent
				d'analyser le trafic sur notre site et d'améliorer nos services.
			</p>
			<p>
				Vous pouvez configurer votre navigateur pour refuser les cookies ou être informé lorsqu'un
				cookie est envoyé.
			</p>
		</div>

		<div class="legal-section">
			<h2 id="limitation">Limitation de responsabilité</h2>
			<p>
				L'utilisateur du site reconnait avoir pris connaissance des présentes mentions légales et
				s'engage à les respecter. L'éditeur ne pourra être tenu responsable des dommages directs ou
				indirects résultant de l'utilisation de ce site.
			</p>
			<p>
				L'éditeur s'efforce de fournir des informations aussi précises que possible. Toutefois, il
				ne pourra être tenu responsable des omissions, des inexactitudes et des carences dans la
				mise à jour, qu'elles soient de son fait ou du fait des tiers partenaires qui lui
				fournissent ces informations.
			</p>
		</div>

		<div class="legal-section">
			<h2 id="applicable-law">Loi applicable</h2>
			<p>
				Ce site est soumis au droit français. En cas de litige, les tribunaux français seront
				compétents.
			</p>
		</div>
	</div>
</section>

<style>
	.legal-header {
		background-color: var(--primary-color);
		color: var(--white);
		padding: 3rem 2rem;
		text-align: center;
	}

	.legal-header h1 {
		font-size: 2.5rem;
		margin-bottom: 1rem;
	}

	.container {
		max-width: 1000px;
		margin: 0 auto;
	}

	.legal-content {
		padding: 3rem 2rem;
	}

	.legal-section {
		margin-bottom: 3rem;
		border-bottom: 1px solid #eee;
		padding-bottom: 2rem;
	}

	.legal-section:last-child {
		border-bottom: none;
	}

	h2 {
		color: var(--primary-color);
		margin-bottom: 1.5rem;
		font-size: 1.75rem;
	}

	h3 {
		color: var(--primary-color);
		margin: 1.5rem 0 1rem 0;
		font-size: 1.25rem;
	}

	p {
		margin-bottom: 1rem;
		line-height: 1.6;
	}

	ul {
		margin-bottom: 1.5rem;
		padding-left: 1.5rem;
	}

	li {
		margin-bottom: 0.5rem;
		line-height: 1.6;
	}

	@media (max-width: 768px) {
		.legal-header {
			padding: 2rem 1rem;
		}

		.legal-header h1 {
			font-size: 2rem;
		}

		.legal-content {
			padding: 2rem 1rem;
		}

		h2 {
			font-size: 1.5rem;
		}
	}
</style>
