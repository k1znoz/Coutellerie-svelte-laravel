/// <reference types="node" />

// Déclarations globales pour le projet

// Extension de l'interface Window pour window.global
interface Window {
	global: Window;
	crypto: Crypto;
}

// Déclaration minimale pour crypto.subtle
interface Crypto {
	subtle: SubtleCrypto;
	getRandomValues<T extends ArrayBufferView | null>(array: T): T;
}

interface SubtleCrypto {
	importKey(
		format: string,
		keyData: BufferSource,
		algorithm: string | { name: string; [key: string]: unknown },
		extractable: boolean,
		keyUsages: string[]
	): Promise<CryptoKey>;

	deriveBits(
		algorithm: string | { name: string; [key: string]: unknown },
		baseKey: CryptoKey,
		length: number
	): Promise<ArrayBuffer>;
}

interface CryptoKey {
	type: string;
	extractable: boolean;
	algorithm: unknown;
	usages: string[];
}

// Déclarations de types pour crypto-browserify
declare module 'crypto-browserify' {
	export function randomBytes(size: number): Buffer | Uint8Array;

	export function pbkdf2Sync(
		password: string | Buffer | Uint8Array,
		salt: string | Buffer | Uint8Array,
		iterations: number,
		keylen: number,
		digest: string
	): Buffer | Uint8Array;

	export function createHash(algorithm: string): Hash;
	export function createHmac(algorithm: string, key: string | Buffer): Hmac;

	interface Hash {
		update(data: string | Buffer): Hash;
		digest(encoding?: string): string | Buffer;
	}

	interface Hmac {
		update(data: string | Buffer): Hmac;
		digest(encoding?: string): string | Buffer;
	}
}

// Déclarations de types pour buffer
declare module 'buffer' {
	export class Buffer {
		static from(data: string, encoding?: string): Buffer;
		static alloc(size: number): Buffer;
		constructor(data: string | number[] | ArrayBuffer | SharedArrayBuffer, encoding?: string);
		toString(encoding?: string): string;

		static isBuffer(obj: unknown): boolean;
		static concat(list: Buffer[], totalLength?: number): Buffer;

		[key: number]: number;
		length: number;
	}
}
