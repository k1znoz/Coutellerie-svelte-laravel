<?php

use Illuminate\Support\Facades\Route;

// Route par défaut - Redirection vers l'interface d'administration Filament
Route::get('/', function () {
    return redirect('/admin');
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

// Route de debug pour vérifier l'état de l'application
Route::get('/debug', function () {
    return response()->json([
        'status' => 'OK',
        'environment' => app()->environment(),
        'filament_panels' => collect(\Filament\Facades\Filament::getPanels())->map(fn($panel) => [
            'id' => $panel->getId(),
            'path' => $panel->getPath(),
            'auth_guard' => $panel->getAuthGuard(),
        ]),
        'users_count' => \App\Models\User::count(),
        'vite_manifest' => file_exists(public_path('build/manifest.json')),
        'storage_linked' => file_exists(public_path('storage')),
    ]);
});

// Route de redirection explicite vers login Filament
Route::get('/login', function () {
    return redirect('/admin/login');
});