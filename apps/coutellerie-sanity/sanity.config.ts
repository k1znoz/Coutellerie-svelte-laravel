import { defineConfig } from 'sanity';
import { deskTool } from 'sanity/desk';
import { visionTool } from '@sanity/vision';
import { schemaTypes } from './schemaTypes';

export default defineConfig({
	name: 'default',
	title: 'Coutellerie CMS',

	projectId: process.env.SANITY_STUDIO_PROJECT_ID || 'focn0owe',
	dataset: process.env.SANITY_STUDIO_DATASET || 'production',

	plugins: [deskTool(), visionTool()],

	schema: {
		types: schemaTypes
	}
});
