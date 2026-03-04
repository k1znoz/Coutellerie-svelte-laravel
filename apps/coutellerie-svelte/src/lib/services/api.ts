import type { ContactFormData, Knife } from '$lib/types';
import { sanityClient, sanityImageUrl } from './sanity';
import { browser } from '$app/environment';

export const API_BASE_URL = '';

interface ApiResponse {
	success: boolean;
	data?: unknown;
	error?: string;
	message?: string;
}

interface SanityImage {
	asset?: {
		_ref?: string;
	};
}

interface SanityKnife {
	_id: string;
	title?: string;
	category?: string;
	description?: string;
	type?: string;
	length?: string;
	material?: string;
	price?: number;
	images?: SanityImage[];
}

const KNIVES_QUERY = `*[_type == "knife"] | order(_updatedAt desc) {
  _id,
  title,
  category,
  description,
  type,
  length,
  material,
  price,
  images
}`;

function mapSanityKnife(knife: SanityKnife): Knife {
	const mappedImages = (knife.images ?? [])
		.map((image) => {
			if (!image?.asset?._ref) {
				return null;
			}
			return sanityImageUrl(image).width(1600).quality(85).auto('format').url();
		})
		.filter((value): value is string => Boolean(value));

	return {
		id: knife._id,
		title: knife.title ?? 'Sans titre',
		category: knife.category ?? 'Non classé',
		description: knife.description ?? '',
		type: knife.type,
		length: knife.length,
		material: knife.material,
		price: knife.price,
		images: mappedImages
	};
}

export async function getAllKnives(): Promise<Knife[]> {
	if (browser) {
		const response = await fetch('/api/knives');

		if (!response.ok) {
			throw new Error(`HTTP error ${response.status}`);
		}

		const payload = (await response.json()) as ApiResponse;
		if (!payload.success || !Array.isArray(payload.data)) {
			throw new Error(payload.error || 'Unable to load knives from server API.');
		}

		return payload.data as Knife[];
	}

	const knives = await sanityClient.fetch<SanityKnife[]>(KNIVES_QUERY);
	return knives.map(mapSanityKnife);
}

export async function getCategories(): Promise<string[]> {
	const knives = await getAllKnives();
	const categories = new Set(knives.map((knife) => knife.category));
	return Array.from(categories).sort();
}

export async function sendContactMessage(contactData: ContactFormData): Promise<ApiResponse> {
	const response = await fetch('/api/contact', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(contactData)
	});

	const payload = (await response.json()) as ApiResponse;

	if (!response.ok) {
		return {
			success: false,
			error: payload.error || `HTTP error ${response.status}`
		};
	}

	return payload;
}
