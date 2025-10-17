<?php

use Illuminate\Support\Facades\Route;

// Route par défaut pour l'API
Route::get('/', function () {
    return response()->json([
        'message' => 'Coutellerie Laravel API',
        'version' => '1.0.0',
        'endpoints' => [
            'health' => '/api/health',
            'knives' => '/api/knives',
            'auth' => '/api/auth',
            'admin' => '/admin'
        ]
    ]);
});

// Login géré directement par Filament sur /admin

// Toutes les autres routes sont gérées par Filament
// Filament utilise automatiquement le préfixe /admin
