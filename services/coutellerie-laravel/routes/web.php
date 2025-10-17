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

// Route login technique pour Laravel (utilisateurs utilisent /admin)
Route::get('/login', function () {
    abort(404); // Empêche l'accès direct à /login
})->name('login');

// Toutes les autres routes sont gérées par Filament
// Filament utilise automatiquement le préfixe /admin
