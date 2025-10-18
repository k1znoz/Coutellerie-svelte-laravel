<?php

use Illuminate\Support\Facades\Route;

// Route par défaut - Redirection vers l'interface d'administration Filament
Route::get('/', function () {
    return redirect('/admin');
});

// Route login - Redirection vers l'authentification Filament (avec nom)
Route::get('/login', function () {
    return redirect('/admin');
})->name('login');

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

