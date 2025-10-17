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

// Route login standard de Laravel
Route::get('/login', function () {
    return view('auth.login'); // Vue login Laravel standard
})->name('login');

// Toutes les autres routes sont gérées par Filament
// Filament utilise automatiquement le préfixe /admin
