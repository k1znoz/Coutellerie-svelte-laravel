import type { Knife, ContactFormData } from '$lib/types.ts';
import { env } from '$env/dynamic/public';

// URL de base de l'API Laravel
export const API_BASE_URL = env.PUBLIC_API_URL || 'http://localhost:8000/api';

// Types pour les réponses API Laravel
interface LaravelApiResponse {
	data: unknown;
	count?: number;
}

interface ContactApiResponse {
	success: boolean;
	message?: string;
	error?: string;
}

/**
 * Récupère tous les couteaux depuis l'API Laravel
 */
export async function getAllKnives(): Promise<Knife[]> {
	const response = await fetch(`${API_BASE_URL}/knives`, {
		method: 'GET',
		headers: {
			'Content-Type': 'application/json'
		}
	});

	if (!response.ok) {
		throw new Error(`HTTP error ${response.status}: ${response.statusText}`);
	}

	const responseText = await response.text();

	try {
		const result = JSON.parse(responseText) as LaravelApiResponse;

		if (result && typeof result === 'object' && Array.isArray(result.data)) {
			return result.data as Knife[];
		} else {
			throw new Error('Format de données inattendu');
		}
	} catch {
		throw new Error(`Erreur de parsing JSON: ${responseText.substring(0, 100)}...`);
	}
}

/**
 * Envoie un message de contact via l'API Laravel
 */
export async function sendContactMessage(
	contactData: ContactFormData
): Promise<ContactApiResponse> {
	const response = await fetch(`${API_BASE_URL}/contact`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(contactData)
	});

	if (!response.ok) {
		throw new Error(`HTTP error ${response.status}: ${response.statusText}`);
	}

	return (await response.json()) as ContactApiResponse;
}

/**
 * Récupère toutes les catégories disponibles depuis l'API Laravel
 */
export async function getCategories(): Promise<string[]> {
	const response = await fetch(`${API_BASE_URL}/knives/categories`, {
		method: 'GET',
		headers: {
			'Content-Type': 'application/json'
		}
	});

	if (!response.ok) {
		throw new Error(`HTTP error ${response.status}: ${response.statusText}`);
	}

	const responseText = await response.text();

	try {
		const result = JSON.parse(responseText) as LaravelApiResponse;

		if (result && typeof result === 'object' && Array.isArray(result.data)) {
			return result.data as string[];
		} else {
			throw new Error('Format de données inattendu');
		}
	} catch {
		throw new Error(`Erreur de parsing JSON: ${responseText.substring(0, 100)}...`);
	}
}
