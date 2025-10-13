/**
 * Utilitaire pour gérer automatiquement les images de la galerie
 * Scanne le dossier gallery et génère les métadonnées automatiquement
 */

export interface GalleryImage {
	src: string;
	alt: string;
	filename: string;
}

/**
 * Génère une description alt simple et descriptive
 */
function generateAlt(filename: string): string {
	return `Création artisanale de coutellerie - ${filename.replace(/\.(webp|jpg|jpeg|png)$/i, '')}`;
}

/**
 * Génère automatiquement la liste des images depuis les assets statiques
 * Note: En production, cette liste serait générée côté serveur ou via un build script
 */
export function generateGalleryImages(): GalleryImage[] {
	// Liste des fichiers d'images dans le dossier gallery
	// Cette liste est maintenue manuellement mais pourrait être automatisée avec un build script
	const imageFiles = [
		'DSC_1478.webp',
		'DSC_1481.webp',
		'DSC_1496.webp',
		'DSC_1509-HDR.webp',
		'DSC_1542.webp',
		'DSC_1583.webp',
		'DSC_1964.webp',
		'DSC_2001.webp',
		'elassastyle-3.jpg',
		'ElassaStyle.webp',
		'max.webp',
		'Wahandle.webp',
		'Wahandle2.webp'
	];

	return imageFiles.map((filename) => ({
		src: `/images/gallery/${filename}`,
		alt: generateAlt(filename),
		filename: filename
	}));
}

/**
 * Sélectionne aléatoirement N images depuis la galerie
 */
export function selectRandomImages(count: number = 3): GalleryImage[] {
	const allImages = generateGalleryImages();

	// Mélange Fisher-Yates
	const shuffled = [...allImages];
	for (let i = shuffled.length - 1; i > 0; i--) {
		const j = Math.floor(Math.random() * (i + 1));
		[shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
	}

	return shuffled.slice(0, count);
}
