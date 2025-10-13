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

// Interface pour les objets knife (couteaux)
export interface Knife {
	id: number;
	title: string;
	category: string;
	images: string[];
	description: string;
	type?: string;
	length?: string;
	material?: string;
	price?: number;
}

// Interfaces pour les composants Contact
export interface ContactFormData {
	name: string;
	email: string;
	subject: string;
	message: string;
}

export interface ContactFormStatus {
	submitted: boolean;
	success: boolean;
	message: string;
}

export interface GalleryItem {
	id: string | number;
	title: string;
	category: string;
	material?: string;
	images?: string[];
	primaryImage?: string; // Pour l'affichage dans la grille
}
