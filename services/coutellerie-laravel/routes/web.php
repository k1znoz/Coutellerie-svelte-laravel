<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Route par défaut pour l'API
Route::get('/', function () {
    return response()->json([
        'message' => 'Coutellerie Laravel API',
        'version' => '1.0.0',
        'endpoints' => [
            'health' => '/health',
            'knives' => '/api/knives',
            'auth' => '/api/auth',
            'admin' => '/admin'
        ]
    ]);
});

// Health check route
Route::get('/health', function () {
    try {
        $dbStatus = DB::connection()->getPdo() ? 'connected' : 'disconnected';
    } catch (\Exception $e) {
        $dbStatus = 'error: ' . $e->getMessage();
    }
    
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'database' => $dbStatus,
        'filament' => class_exists('Filament\Panel') ? 'installed' : 'not installed'
    ]);
});

// Redirection de /login vers /admin/login pour Filament
Route::get('/login', function () {
    return redirect('/admin/login');
});

// Toutes les autres routes sont gérées par Filament
// Filament utilise automatiquement le préfixe /admin