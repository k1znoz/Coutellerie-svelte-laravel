// Service API pour interagir avec le backend

import type { Knife, ContactFormData } from '$lib/types.ts';

// URL de base de l'API
export const API_BASE_URL = 'http://localhost:8000/api/';

// Types pour les options et réponses API
interface ApiOptions {
	headers?: HeadersInit;
}

interface ApiResponse {
	success: boolean;
	data?: unknown;
	error?: string;
	message?: string;
}

interface AuthResponse {
	success: boolean;
	token?: string;
	refreshToken?: string;
	expiresIn?: number;
	error?: string;
}

/**
 * Vérifie les paramètres d'authentification pour les requêtes à l'API
 * @returns Headers avec token d'authentification et CSRF si disponible
 */
function getAuthHeaders(): HeadersInit {
	const headers: HeadersInit = {
		'Content-Type': 'application/json'
	};

	const token = sessionStorage.getItem('adminToken');
	if (token) {
		headers['Authorization'] = `Bearer ${token}`;
	}

	const csrfToken = sessionStorage.getItem('csrfToken');
	if (csrfToken) {
		headers['X-CSRF-Token'] = csrfToken;
	}

	return headers;
}

/**
 * Récupère tous les couteaux depuis l'API
 * @param options Options supplémentaires pour la requête
 * @returns Liste des couteaux
 */
export async function getAllKnives(options: ApiOptions = {}): Promise<Knife[]> {
	const headers = options.headers || {
		'Content-Type': 'application/json'
	};

	const response = await fetch(`${API_BASE_URL}knives`, {
		method: 'GET',
		headers
	});

	if (!response.ok) {
		throw new Error(`HTTP error ${response.status}: ${response.statusText}`);
	}

	const responseText = await response.text();

	try {
		const result = JSON.parse(responseText) as ApiResponse;

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
 * Crée un nouveau couteau via l'API
 * @param knife Données du couteau
 * @param options Options supplémentaires pour la requête
 * @returns Résultat de l'opération
 */
export async function createKnife(
	knife: Partial<Knife>,
	options: ApiOptions = {}
): Promise<ApiResponse> {
	const headers = options.headers || getAuthHeaders();

	if (!knife.title || !knife.category || !knife.description) {
		throw new Error('Champs requis manquants: titre, catégorie et description sont obligatoires');
	}

	const response = await fetch(`${API_BASE_URL}api/knives.php`, {
		method: 'POST',
		headers,
		body: JSON.stringify(knife)
	});

	const responseText = await response.text();

	if (!response.ok) {
		let errorMessage = `HTTP error ${response.status}`;
		try {
			if (responseText) {
				if (responseText.indexOf('<') >= 0 && responseText.indexOf('>') >= 0) {
					errorMessage = `${errorMessage}: Erreur côté serveur (HTML reçu)`;
				} else {
					const errorObj = JSON.parse(responseText) as ApiResponse;
					errorMessage = errorObj.error || errorMessage;
				}
			}
		} catch {
			errorMessage = `${errorMessage}: ${responseText.substring(0, 100)}${responseText.length > 100 ? '...' : ''}`;
		}
		throw new Error(errorMessage);
	}

	try {
		if (responseText) {
			if (responseText.indexOf('<') >= 0 && responseText.indexOf('>') >= 0) {
				throw new Error('Réponse HTML reçue au lieu de JSON');
			}
			const result = JSON.parse(responseText) as ApiResponse;
			return result;
		}
		return { success: true };
	} catch {
		const truncatedText = responseText.substring(0, 100) + (responseText.length > 100 ? '...' : '');
		throw new Error(`Erreur de parsing JSON: ${truncatedText}`);
	}
}

/**
 * Met à jour un couteau existant via l'API
 * @param knife Données du couteau à mettre à jour
 * @param options Options supplémentaires pour la requête
 * @returns Résultat de l'opération
 */
export async function updateKnife(knife: Knife, options: ApiOptions = {}): Promise<ApiResponse> {
	const headers = options.headers || getAuthHeaders();

	const response = await fetch(`${API_BASE_URL}api/knives.php`, {
		method: 'PUT',
		headers,
		body: JSON.stringify(knife)
	});

	const responseText = await response.text();

	if (!response.ok) {
		let errorMessage = `HTTP error ${response.status}`;
		try {
			if (responseText) {
				const errorObj = JSON.parse(responseText) as ApiResponse;
				errorMessage = errorObj.error || errorMessage;
			}
		} catch {
			errorMessage = `${errorMessage}: ${responseText}`;
		}
		throw new Error(errorMessage);
	}

	try {
		if (responseText) {
			const result = JSON.parse(responseText) as ApiResponse;
			return result;
		}
		return { success: true };
	} catch {
		throw new Error(`Erreur de parsing JSON: ${responseText.substring(0, 100)}...`);
	}
}

/**
 * Supprime un couteau via l'API
 * @param id ID du couteau à supprimer
 * @param options Options supplémentaires pour la requête
 * @returns Résultat de l'opération
 */
export async function deleteKnife(id: number, options: ApiOptions = {}): Promise<ApiResponse> {
	const headers = options.headers || getAuthHeaders();

	const response = await fetch(`${API_BASE_URL}api/knives.php?id=${id}`, {
		method: 'DELETE',
		headers
	});

	const responseText = await response.text();

	if (!response.ok) {
		let errorMessage = `HTTP error ${response.status}`;
		try {
			if (responseText) {
				const errorObj = JSON.parse(responseText) as ApiResponse;
				errorMessage = errorObj.error || errorMessage;
			}
		} catch {
			errorMessage = `${errorMessage}: ${responseText}`;
		}
		throw new Error(errorMessage);
	}

	try {
		if (responseText) {
			const result = JSON.parse(responseText) as ApiResponse;
			return result;
		}
		return { success: true };
	} catch {
		throw new Error(`Erreur de parsing JSON: ${responseText.substring(0, 100)}...`);
	}
}

/**
 * Authentifie un utilisateur via l'API
 * @param username Nom d'utilisateur
 * @param password Mot de passe
 * @returns Résultat de l'authentification
 */
export async function login(username: string, password: string): Promise<AuthResponse> {
	const response = await fetch(`${API_BASE_URL}auth.php`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify({
			action: 'login',
			username,
			password
		})
	});

	const result = (await response.json()) as AuthResponse;

	if (result.success) {
		if (result.token) {
			sessionStorage.setItem('adminToken', result.token);
		}
		if (result.expiresIn) {
			sessionStorage.setItem('tokenExpires', (Date.now() + result.expiresIn * 1000).toString());
		}
		sessionStorage.setItem('adminUsername', username);
		if (result.refreshToken) {
			localStorage.setItem('refreshToken', result.refreshToken);
		}
	}

	return result;
}

/**
 * Rafraîchit le token d'accès avec un token de rafraîchissement
 * @returns Nouveaux tokens
 */
export async function refreshAccessToken(): Promise<AuthResponse> {
	const refreshToken = localStorage.getItem('refreshToken');

	if (!refreshToken) {
		throw new Error('No refresh token available');
	}

	const response = await fetch(`${API_BASE_URL}auth.php`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify({
			action: 'refresh',
			refreshToken
		})
	});

	const result = (await response.json()) as AuthResponse;

	if (result.token) {
		sessionStorage.setItem('adminToken', result.token);
		if (result.expiresIn) {
			sessionStorage.setItem('tokenExpires', (Date.now() + result.expiresIn * 1000).toString());
		}
		if (result.refreshToken) {
			localStorage.setItem('refreshToken', result.refreshToken);
		}
	}

	return result;
}

/**
 * Vérifie la validité d'un token existant
 * @param token Token à vérifier
 * @returns Résultat de la vérification
 */
export async function verifyToken(token: string): Promise<AuthResponse> {
	const response = await fetch(`${API_BASE_URL}auth.php`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify({
			action: 'verify',
			token
		})
	});

	return (await response.json()) as AuthResponse;
}

/**
 * Déconnexion - supprime les tokens stockés
 */
export function logout(): void {
	sessionStorage.removeItem('adminToken');
	sessionStorage.removeItem('tokenExpires');
	sessionStorage.removeItem('csrfToken');
	localStorage.removeItem('refreshToken');
}

/**
 * Récupère toutes les catégories disponibles depuis l'API
 * @param options Options supplémentaires pour la requête
 * @returns Liste des catégories
 */
export async function getCategories(options: ApiOptions = {}): Promise<string[]> {
	const headers = options.headers || {
		'Content-Type': 'application/json'
	};

	const response = await fetch(`${API_BASE_URL}knives/categories`, {
		method: 'GET',
		headers
	});

	if (!response.ok) {
		throw new Error(`HTTP error ${response.status}: ${response.statusText}`);
	}

	const responseText = await response.text();

	try {
		const result = JSON.parse(responseText) as ApiResponse;

		if (result && typeof result === 'object' && Array.isArray(result.data)) {
			return result.data as string[];
		} else {
			throw new Error('Format de données inattendu');
		}
	} catch {
		throw new Error(`Erreur de parsing JSON: ${responseText.substring(0, 100)}...`);
	}
}

/**
 * Envoie un message de contact via l'API
 * @param contactData Les données du formulaire de contact
 * @returns La réponse de l'API
 */
export async function sendContactMessage(contactData: ContactFormData): Promise<ApiResponse> {
	const response = await fetch(`${API_BASE_URL}contact`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(contactData)
	});

	if (!response.ok) {
		throw new Error(`HTTP error ${response.status}`);
	}

	return (await response.json()) as ApiResponse;
}
