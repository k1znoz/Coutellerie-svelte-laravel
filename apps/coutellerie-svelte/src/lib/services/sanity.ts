import { createClient } from '@sanity/client';
import imageUrlBuilder from '@sanity/image-url';
import { env } from '$env/dynamic/public';

const projectId = env.PUBLIC_SANITY_PROJECT_ID;
const dataset = env.PUBLIC_SANITY_DATASET;
const apiVersion = env.PUBLIC_SANITY_API_VERSION || '2025-01-01';

if (!projectId || !dataset) {
	throw new Error(
		'Missing Sanity configuration. Define PUBLIC_SANITY_PROJECT_ID and PUBLIC_SANITY_DATASET in your .env file.'
	);
}

export const sanityClient = createClient({
	projectId,
	dataset,
	apiVersion,
	useCdn: true,
	perspective: 'published'
});

const imageBuilder = imageUrlBuilder(sanityClient);

export function sanityImageUrl(source: any) {
	return imageBuilder.image(source);
}
