<?php

use Illuminate\Support\Facades\Route;

// Route par défaut - Page d'accueil simple
Route::get('/', function () {
    return view('welcome');
});

// Route API info (accessible via /api-info)
Route::get('/api-info', function () {
    return response()->json([
        'message' => 'Coutellerie Laravel API',
        'version' => '1.0.0',
        'endpoints' => [
            'health' => '/api/health',
            'knives' => '/api/knives',
            'auth' => '/api/auth',
        ]
    ]);
});

