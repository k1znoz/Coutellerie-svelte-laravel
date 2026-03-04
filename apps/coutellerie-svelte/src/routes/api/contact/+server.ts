import { json } from '@sveltejs/kit';
import { createClient } from '@sanity/client';
import { env as publicEnv } from '$env/dynamic/public';
import { env as privateEnv } from '$env/dynamic/private';
import type { RequestHandler } from './$types';

const projectId = publicEnv.PUBLIC_SANITY_PROJECT_ID;
const dataset = publicEnv.PUBLIC_SANITY_DATASET;
const apiVersion = publicEnv.PUBLIC_SANITY_API_VERSION || '2025-01-01';

export const POST: RequestHandler = async ({ request, getClientAddress }) => {
	const sanityApiToken = privateEnv.SANITY_API_TOKEN || process.env.SANITY_API_TOKEN;

	const sanityWriteClient =
		projectId && dataset && sanityApiToken
			? createClient({
					projectId,
					dataset,
					apiVersion,
					token: sanityApiToken,
					useCdn: false
				})
			: null;

	if (!sanityWriteClient) {
		const missing = [
			!projectId ? 'PUBLIC_SANITY_PROJECT_ID' : null,
			!dataset ? 'PUBLIC_SANITY_DATASET' : null,
			!sanityApiToken ? 'SANITY_API_TOKEN' : null
		]
			.filter(Boolean)
			.join(', ');

		return json(
			{
				success: false,
				error: `Sanity is not configured for contact form submissions. Missing: ${missing}`
			},
			{ status: 503 }
		);
	}

	const body = (await request.json()) as {
		name?: string;
		email?: string;
		subject?: string;
		message?: string;
	};

	const normalizedBody = {
		name: body.name?.trim() ?? '',
		email: body.email?.trim() ?? '',
		subject: body.subject?.trim() ?? '',
		message: body.message?.trim() ?? ''
	};

	if (!normalizedBody.name || !normalizedBody.email || !normalizedBody.subject || !normalizedBody.message) {
		return json({ success: false, error: 'Missing required fields.' }, { status: 400 });
	}

	try {
		const draftId = `drafts.contactMessage.${crypto.randomUUID()}`;

		await sanityWriteClient.create({
			_id: draftId,
			_type: 'contactMessage',
			name: normalizedBody.name,
			email: normalizedBody.email,
			subject: normalizedBody.subject,
			message: normalizedBody.message,
			source: 'website',
			submittedAt: new Date().toISOString(),
			ipAddress: getClientAddress()
		});

		return json({ success: true, message: 'Message stored in Sanity.' });
	} catch (error) {
		const message = error instanceof Error ? error.message : 'Unable to send your message.';
		return json({ success: false, error: message }, { status: 500 });
	}
};
