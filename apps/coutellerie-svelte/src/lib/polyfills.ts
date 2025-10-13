// Polyfills minimalistes pour l'environnement du navigateur

// Vérification de l'environnement navigateur
if (typeof window !== 'undefined') {
	// Polyfill pour `global` (requis par certaines bibliothèques Node.js)
	if (!('global' in window)) {
		Object.defineProperty(window, 'global', {
			value: window,
			writable: true,
			configurable: true
		});
	}

	// Polyfill pour `process.env` (requis par certaines bibliothèques)
	if (!('process' in window)) {
		Object.defineProperty(window, 'process', {
			value: { env: {} },
			writable: true,
			configurable: true
		});
	}
}

// Export pour permettre l'import dans d'autres fichiers
export {};
