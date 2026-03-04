<script lang="ts">
	import { createEventDispatcher } from 'svelte';
	import type { ContactFormData, ContactFormStatus } from '$lib/types';
	import { sendContactMessage } from '$lib/services/api';

	let formData: ContactFormData = {
		name: '',
		email: '',
		subject: '',
		message: ''
	};

	let formStatus: ContactFormStatus = {
		submitted: false,
		success: false,
		message: ''
	};
	let isSubmitting = false;
	const dispatch = createEventDispatcher();

	async function handleSubmit() {
		isSubmitting = true;
		formStatus = {
			submitted: false,
			success: false,
			message: ''
		};
		try {
			const response = await sendContactMessage(formData);
			formStatus.submitted = true;
			if (response.success) {
				formStatus.success = true;
				formStatus.message =
					'Votre message a été envoyé avec succès! Je vous contacterai dès que possible.';
				formData = { name: '', email: '', subject: '', message: '' };
				dispatch('success');
			} else {
				formStatus.success = false;
				formStatus.message =
					response.error || "Une erreur est survenue lors de l'envoi du message. Veuillez réessayer.";
			}
		} catch {
			formStatus.submitted = true;
			formStatus.success = false;
			formStatus.message =
				"Une erreur est survenue lors de l'envoi du message. Veuillez réessayer.";
		} finally {
			isSubmitting = false;
		}
	}
</script>

<div class="contact-form">
	<h2>Envoyez-moi un message</h2>
	{#if formStatus.submitted && formStatus.success}
		<div class="success-message">
			<i class="fas fa-check-circle"></i>
			<p>{formStatus.message}</p>
		</div>
	{:else}
		<form on:submit|preventDefault={handleSubmit}>
			{#if formStatus.submitted && !formStatus.success && formStatus.message}
				<div class="error-message">
					<p>{formStatus.message}</p>
				</div>
			{/if}

			<div class="form-group">
				<label for="name">Nom complet <span>*</span></label>
				<input type="text" id="name" bind:value={formData.name} required placeholder="Votre nom" />
			</div>
			<div class="form-group">
				<label for="email">Email <span>*</span></label>
				<input
					type="email"
					id="email"
					bind:value={formData.email}
					required
					placeholder="Votre email"
				/>
			</div>
			<div class="form-group">
				<label for="subject">Sujet <span>*</span></label>
				<input
					type="text"
					id="subject"
					bind:value={formData.subject}
					required
					placeholder="Sujet de votre message"
				/>
			</div>
			<div class="form-group">
				<label for="message">Message <span>*</span></label>
				<textarea
					id="message"
					bind:value={formData.message}
					rows="6"
					required
					placeholder="Votre message"
				></textarea>
			</div>
			<button
				type="submit"
				class="btn btn-primary"
				disabled={isSubmitting}
			>
				{#if isSubmitting}
					<i class="fas fa-spinner fa-spin"></i> Envoi en cours...
				{:else}
					Envoyer le message
				{/if}
			</button>
		</form>
	{/if}
</div>

<style>
	.contact-form h2 {
		margin-bottom: 2rem;
	}

	.form-group {
		margin-bottom: 1.5rem;
	}

	.form-group label {
		display: block;
		margin-bottom: 0.5rem;
		font-weight: 600;
	}

	.form-group label span {
		color: #e74c3c;
	}

	.form-group input,
	.form-group textarea {
		width: 100%;
		padding: 0.75rem 1rem;
		border: 1px solid #ddd;
		border-radius: 0.25rem;
		font-size: 1rem;
		transition:
			border-color 0.3s,
			box-shadow 0.3s;
	}

	.form-group input:focus,
	.form-group textarea:focus {
		border-color: var(--primary-color);
		box-shadow: 0 0 0 2px rgba(var(--primary-rgb), 0.2);
		outline: none;
	}
	.btn {
		display: inline-block;
		padding: 0.75rem 1.5rem;
		border-radius: 0.25rem;
		text-decoration: none;
		font-weight: 600;
		transition: all 0.3s;
		cursor: pointer;
		border: none;
		font-size: 1rem;
	}

	.btn-primary {
		background-color: var(--primary-color);
		color: var(--white);
	}

	.btn-primary:hover {
		background-color: var(--primary-dark);
	}

	.btn:disabled {
		opacity: 0.7;
		cursor: not-allowed;
	}

	.success-message {
		background-color: #d4edda;
		color: #155724;
		padding: 2rem;
		border-radius: 0.25rem;
		text-align: center;
	}

	.error-message {
		background-color: #f8d7da;
		color: #721c24;
		padding: 1rem;
		margin-bottom: 1rem;
		border-radius: 0.25rem;
	}

	.success-message i {
		font-size: 3rem;
		margin-bottom: 1rem;
	}

	.success-message p {
		font-size: 1.1rem;
	}
</style>
