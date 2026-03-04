const PROJECT_ID_PATTERN = /^[a-z0-9-]+$/;

function cleanEnvValue(value?: string) {
	if (!value) {
		return '';
	}

	const trimmed = value.trim();
	return trimmed.replace(/^['\"]|['\"]$/g, '').trim();
}

function extractProjectIdFromUrl(value: string) {
	try {
		const parsed = new URL(value);
		const hostParts = parsed.hostname.split('.');

		if (
			hostParts.length >= 3 &&
			(hostParts[1] === 'api' || hostParts[1] === 'apicdn') &&
			hostParts[2] === 'sanity'
		) {
			return hostParts[0];
		}

		const pathParts = parsed.pathname.split('/').filter(Boolean);
		if (pathParts.length >= 2 && (pathParts[0] === 'images' || pathParts[0] === 'files')) {
			return pathParts[1];
		}

		return value;
	} catch {
		return value;
	}
}

export function getSanityProjectId(rawValue?: string) {
	const cleanedValue = cleanEnvValue(rawValue);
	const extractedValue = extractProjectIdFromUrl(cleanedValue);

	if (!extractedValue) {
		throw new Error(
			'Missing Sanity project ID. Define PUBLIC_SANITY_PROJECT_ID with your Sanity project ID (for example: focn0owe).'
		);
	}

	if (!PROJECT_ID_PATTERN.test(extractedValue)) {
		throw new Error(
			`Invalid PUBLIC_SANITY_PROJECT_ID value: "${extractedValue}". It must contain only a-z, 0-9 and dashes and must be your Sanity project ID (not Vercel project ID).`
		);
	}

	return extractedValue;
}

export function getSanityDataset(rawValue?: string) {
	const cleanedValue = cleanEnvValue(rawValue);

	if (!cleanedValue) {
		throw new Error('Missing Sanity dataset. Define PUBLIC_SANITY_DATASET (for example: production).');
	}

	return cleanedValue;
}
