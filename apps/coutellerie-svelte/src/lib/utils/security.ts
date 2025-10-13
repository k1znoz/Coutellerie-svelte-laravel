/**
 * Sanitize une chaîne de caractères pour prévenir les attaques XSS
 * @param input La chaîne à sanitizer
 * @returns La chaîne sanitizée
 */
export function sanitizeInput(input: string): string {
	if (!input) return '';

	return input
		.replace(/</g, '&lt;')
		.replace(/>/g, '&gt;')
		.replace(/"/g, '&quot;')
		.replace(/'/g, '&#039;');
}

/**
 * Valide une URL d'image pour s'assurer qu'elle est sécurisée
 * @param url L'URL à valider
 * @returns true si l'URL est valide et sécurisée
 */
export function validateImageUrl(url: string): boolean {
	if (!url) return false;

	// Accepter uniquement les chemins relatifs ou les URLs HTTPS
	if (url.startsWith('/') || url.startsWith('https://')) {
		// Vérifier qu'il s'agit bien d'une image
		return /\.(jpg|jpeg|png|gif|webp|svg)$/i.test(url);
	}

	return false;
}
