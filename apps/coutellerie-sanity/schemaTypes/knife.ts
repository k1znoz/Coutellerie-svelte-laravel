import { defineField, defineType } from 'sanity';

export const knifeType = defineType({
	name: 'knife',
	title: 'Couteau',
	type: 'document',
	fields: [
		defineField({ name: 'title', title: 'Titre', type: 'string', validation: (Rule) => Rule.required() }),
		defineField({ name: 'category', title: 'Catégorie', type: 'string', validation: (Rule) => Rule.required() }),
		defineField({ name: 'description', title: 'Description', type: 'text', validation: (Rule) => Rule.required() }),
		defineField({ name: 'type', title: 'Type', type: 'string' }),
		defineField({ name: 'length', title: 'Longueur', type: 'string' }),
		defineField({ name: 'material', title: 'Matériau', type: 'string' }),
		defineField({ name: 'price', title: 'Prix', type: 'number' }),
		defineField({
			name: 'images',
			title: 'Images',
			type: 'array',
			of: [{ type: 'image', options: { hotspot: true } }],
			validation: (Rule) => Rule.min(1)
		})
	],
	preview: {
		select: {
			title: 'title',
			subtitle: 'category',
			media: 'images.0'
		}
	}
});
