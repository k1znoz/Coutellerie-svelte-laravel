import { API_BASE_URL } from './api.js';
import {
	generateAuthToken as webCryptoGenerateAuthToken,
	validateToken as webCryptoValidateToken,
	hashPassword as webCryptoHashPassword
} from './webCrypto.js';

/**
 * Valide le mot de passe avec le backend
 * @param username Nom d'utilisateur à vérifier
 * @param password Mot de passe à vérifier
 */
export async function verifyPassword(username: string, password: string): Promise<boolean> {
	const response = await fetch(`${API_BASE_URL}/auth.php`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			Origin: window.location.origin
		},
		body: JSON.stringify({
			action: 'login',
			username,
			password
		})
	});

	if (!response.ok) {
		// Si le statut n'est pas 2xx, on tente quand même de lire la réponse
		try {
			await response.json();
			return false;
		} catch {
			// Si on ne peut pas lire la réponse comme du JSON
			await response.text();
			return false;
		}
	}

	// Tentative de récupérer les données JSON
	const data = await response.json();

	// Si la connexion a réussi, enregistrer le token
	if (data.success && data.token) {
		sessionStorage.setItem('adminToken', data.token);
		sessionStorage.setItem('refreshToken', data.refreshToken);
		sessionStorage.setItem('tokenExpires', (Date.now() + data.expiresIn * 1000).toString());
		return true;
	}

	return false;
}

/**
 * Génère un jeton d'authentification sécurisé avec expiration
 */
export function generateAuthToken(): { token: string; expires: number } {
	return webCryptoGenerateAuthToken();
}

/**
 * Vérifie si un jeton est valide et non expiré
 */
export function validateToken(token: string, expirationTime: number): boolean {
	return webCryptoValidateToken(token, expirationTime);
}

/**
 * Hash un mot de passe avec l'algorithme PBKDF2
 * @param password Mot de passe à hasher
 * @param salt Sel cryptographique
 */
export async function hashPassword(password: string, salt: string): Promise<string> {
	return webCryptoHashPassword(password, salt);
}

/**
 * Initialise le module d'authentification
 * (Cette fonction sera appelée au démarrage de l'application)
 */
export function initAuth(): void {
	// Vérifier la présence du token d'authentification
	if (typeof window !== 'undefined') {
		const token = sessionStorage.getItem('adminToken');
		const expiresStr = sessionStorage.getItem('tokenExpires');
		const expires = expiresStr ? parseInt(expiresStr, 10) : 0;

		// Vérifier si le token est valide
		if (token && validateToken(token, expires)) {
			// Token valide trouvé
		} else {
			// Aucun token valide
		}
	}
}
