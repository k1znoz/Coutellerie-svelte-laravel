<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => [
        'api/*', 
        'sanctum/csrf-cookie', 
        'images/*', 
        'admin/*',
        'storage/*'
    ],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => [
        // Développement local
        'http://localhost:5173',
        'http://localhost:3000', 
        'http://localhost:4173',
        'http://127.0.0.1:5173',
        'http://127.0.0.1:3000',
        // Production Railway Backend
        env('APP_URL', 'https://coutellerie-production.up.railway.app'),
        // Production Frontend (SvelteKit peut être sur différents services)
        env('FRONTEND_URL', 'https://coutellerie-frontend.vercel.app'),
        // Autoriser Railway temporairement pour debug
        '*', // À retirer en production finale
    ],

    'allowed_origins_patterns' => [
        '/^https:\/\/.*\.railway\.app$/',
        '/^https:\/\/.*\.vercel\.app$/',
    ],

    'allowed_headers' => [
        'Accept',
        'Authorization',
        'Content-Type',
        'Origin',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'X-XSRF-TOKEN',
        'Cache-Control',
        'Pragma',
        'Expires',
    ],

    'exposed_headers' => ['X-Pagination-Total', 'X-Pagination-Per-Page'],

    'max_age' => 86400, // 24 heures

    'supports_credentials' => true,

];
