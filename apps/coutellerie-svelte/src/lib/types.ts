// Interfaces et types partagés pour l'application

// Interface pour les propriétés du composant HeroSection
export interface HeroProps {
	title: string;
	subtitle: string;
	backgroundImage?: string;
	type?: 'about' | 'gallery' | 'admin' | 'contact' | 'home';
	ctaText?: string;
	ctaLink?: string;
}

// Interface pour les propriétés spécifiques du hero de la page About
export interface HeroPropsAbout extends HeroProps {
	type: 'about';
}

// Interface pour les propriétés spécifiques du hero de la page Gallery
export interface HeroPropsGallery extends HeroProps {
	type: 'gallery';
}

// Interface pour les propriétés spécifiques du hero de la page Admin
export interface HeroPropsAdmin extends HeroProps {
	type: 'admin';
	isSecured?: boolean;
}
// Interface pour les propriétés spécifiques du hero de la page Admin
export interface HeroPropsContact extends HeroProps {
	type: 'contact';
}

// Interface pour les événements de la timeline
export interface TimelineEvent {
	year: string;
	title: string;
	description: string;
	side: 'left' | 'right';
}

// Interface pour les certifications
export interface Certification {
	name: string;
	issuer: string;
	year: string;
	image?: string;
}

// Interface pour les valeurs
export interface Value {
	title: string;
	description: string;
	icon: string;
}

// Interface pour les objets knife (couteaux) - aligné avec l'API Laravel
export interface Knife {
	id?: number;
	name: string;           // Changé de "title" vers "name" pour correspondre à la DB
	category: string;
	description: string;
	images: string[];       // Tableau d'URLs d'images
	type: string;           // Obligatoire selon la migration
	length: string;         // Obligatoire selon la migration
	material: string;       // Obligatoire selon la migration
	price: number;          // Obligatoire selon la migration (decimal)
	created_at?: string;
	updated_at?: string;
}

// Interfaces pour les composants Contact - aligné avec l'API Laravel
export interface ContactFormData {
	name: string;           // Obligatoire selon la migration
	email: string;
	subject?: string;       // Optionnel selon la migration
	message: string;
}

export interface ContactFormStatus {
	submitted: boolean;
	success: boolean;
	message: string;
}

export interface GalleryItem {
	id: number;
	name: string;
	category: string;
	material: string;
	images: string[];
	primaryImage?: string; // Pour l'affichage dans la grille
	type: string;
	length: string;
	price: number;
}
