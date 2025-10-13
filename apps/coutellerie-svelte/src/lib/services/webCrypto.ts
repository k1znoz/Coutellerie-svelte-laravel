/**
 * Service d'authentification utilisant Web Crypto API
 */

// Détection de l'environnement
const isBrowser = typeof window !== 'undefined';

/**
 * Génère un nombre aléatoire d'octets et le renvoie sous forme de chaîne hexadécimale
 */
export function generateRandomBytes(length: number): string {
	if (isBrowser) {
		// Utiliser Web Crypto API en environnement navigateur
		const buffer = new Uint8Array(length);
		window.crypto.getRandomValues(buffer);
		return Array.from(buffer)
			.map((b) => b.toString(16).padStart(2, '0'))
			.join('');
	} else {
		// Fallback pour SSR (moins aléatoire, à utiliser seulement pour le développement)
		let result = '';
		const characters = 'abcdef0123456789';
		const charactersLength = characters.length;
		for (let i = 0; i < length * 2; i++) {
			result += characters.charAt(Math.floor(Math.random() * charactersLength));
		}
		return result;
	}
}

/**
 * Génère un jeton d'authentification sécurisé avec expiration
 */
export function generateAuthToken(): { token: string; expires: number } {
	// Générer un jeton aléatoire de 32 octets
	const token = generateRandomBytes(32);

	// Date d'expiration (4 heures à partir de maintenant)
	const expires = Date.now() + 4 * 60 * 60 * 1000;

	return { token, expires };
}

/**
 * Vérifie si un jeton est valide et non expiré
 */
export function validateToken(token: string, expirationTime: number): boolean {
	return !!token && Date.now() < expirationTime;
}

/**
 * Génère un sel cryptographique aléatoire
 */
export function generateSalt(length = 16): string {
	return generateRandomBytes(length);
}

/**
 * Hash un mot de passe avec l'algorithme PBKDF2 (version Web Crypto)
 * Note: Cette fonction ne peut s'exécuter que dans le navigateur
 */
export async function hashPassword(
	password: string,
	salt: string,
	iterations = 10000,
	keyLength = 64
): Promise<string> {
	if (!isBrowser) {
		// Retourner une chaîne vide en cas d'environnement SSR
		return '';
	}

	// Convertir mot de passe et sel en ArrayBuffer
	const encoder = new TextEncoder();
	const passwordBuffer = encoder.encode(password);

	// Convertir le sel hexadécimal en ArrayBuffer
	const saltBuffer = new Uint8Array(
		(salt.match(/.{1,2}/g) || []).map((byte) => parseInt(byte, 16))
	);

	// Dériver la clé avec PBKDF2
	const keyMaterial = await window.crypto.subtle.importKey(
		'raw',
		passwordBuffer,
		{ name: 'PBKDF2' },
		false,
		['deriveBits']
	);

	const derivedBits = await window.crypto.subtle.deriveBits(
		{
			name: 'PBKDF2',
			salt: saltBuffer,
			iterations: iterations,
			hash: 'SHA-512'
		},
		keyMaterial,
		keyLength * 8 // bits
	);

	// Convertir en chaîne hexadécimale
	const hashArray = Array.from(new Uint8Array(derivedBits));
	return hashArray.map((b) => b.toString(16).padStart(2, '0')).join('');
}
