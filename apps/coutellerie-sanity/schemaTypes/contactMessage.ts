import { defineField, defineType } from 'sanity';

export const contactMessageType = defineType({
	name: 'contactMessage',
	title: 'Message de contact',
	type: 'document',
	fields: [
		defineField({ name: 'name', title: 'Nom', type: 'string', validation: (Rule) => Rule.required() }),
		defineField({ name: 'email', title: 'Email', type: 'string', validation: (Rule) => Rule.required().email() }),
		defineField({ name: 'subject', title: 'Sujet', type: 'string', validation: (Rule) => Rule.required() }),
		defineField({ name: 'message', title: 'Message', type: 'text', validation: (Rule) => Rule.required() }),
		defineField({ name: 'source', title: 'Source', type: 'string' }),
		defineField({ name: 'submittedAt', title: 'Date d\'envoi', type: 'datetime' }),
		defineField({ name: 'ipAddress', title: 'Adresse IP', type: 'string' })
	],
	preview: {
		select: {
			title: 'subject',
			subtitle: 'email'
		}
	}
});
