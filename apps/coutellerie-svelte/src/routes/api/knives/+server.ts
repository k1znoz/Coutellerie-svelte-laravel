import { json } from '@sveltejs/kit';
import { getAllKnives } from '$lib/services/api';
import type { RequestHandler } from './$types';

export const GET: RequestHandler = async () => {
	try {
		const knives = await getAllKnives();
		return json({ success: true, data: knives });
	} catch {
		return json(
			{ success: false, error: 'Unable to load knives from Sanity.' },
			{ status: 500 }
		);
	}
};
