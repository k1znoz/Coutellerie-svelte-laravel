// See https://svelte.dev/docs/kit/types#app.d.ts
// for information about these interfaces
declare global {
	namespace App {
		interface PublicEnv {
			PUBLIC_SANITY_PROJECT_ID: string;
			PUBLIC_SANITY_DATASET: string;
			PUBLIC_SANITY_API_VERSION?: string;
		}

		interface PrivateEnv {
			SANITY_API_TOKEN: string;
		}

		// interface Error {}
		// interface Locals {}
		// interface PageData {}
		// interface PageState {}
		// interface Platform {}
	}
}

export {};
