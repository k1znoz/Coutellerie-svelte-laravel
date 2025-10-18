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

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'images/*', 'admin/*'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => [
        // Développement local
        'http://localhost:5173',
        'http://localhost:3000', 
        'http://localhost:4173',
        // Production
        'https://coutellerie-production.up.railway.app',
        // Ajoutez l'URL de votre app Svelte en production
        env('FRONTEND_URL', 'https://coutellerie-frontend.vercel.app/'),
    ],

    'allowed_origins_patterns' => [
        '/^https:\/\/.*\.railway\.app$/',
        '/^https:\/\/.*\.vercel\.app$/',
    ],

    'allowed_headers' => [
        'Accept',
        'Authorization',
        'Content-Type',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'X-XSRF-TOKEN',
    ],

    'exposed_headers' => ['X-Pagination-Total', 'X-Pagination-Per-Page'],

    'max_age' => 86400, // 24 heures

    'supports_credentials' => true,

];
