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
            'admin' => '/admin'
        ]
    ]);
});

// Route de login qui redirige vers Filament (nécessaire pour Laravel Auth)
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

// Toutes les autres routes sont gérées par Filament
// Filament utilise automatiquement le préfixe /admin
