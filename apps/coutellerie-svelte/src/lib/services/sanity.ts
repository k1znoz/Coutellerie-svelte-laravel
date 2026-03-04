import { createClient } from '@sanity/client';
import imageUrlBuilder from '@sanity/image-url';
import { env } from '$env/dynamic/public';
import { getSanityDataset, getSanityProjectId } from '$lib/utils/sanityEnv';

const projectId = getSanityProjectId(env.PUBLIC_SANITY_PROJECT_ID);
const dataset = getSanityDataset(env.PUBLIC_SANITY_DATASET);
const apiVersion = env.PUBLIC_SANITY_API_VERSION || '2025-01-01';

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
