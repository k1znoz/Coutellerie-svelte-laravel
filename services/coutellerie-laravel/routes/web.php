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

// Route de debug temporaire pour vérifier Filament
Route::get('/debug-filament', function () {
    $panels = \Filament\Facades\Filament::getPanels();
    $routes = collect(Route::getRoutes())->filter(function ($route) {
        return str_contains($route->uri(), 'admin');
    })->map(function ($route) {
        return [
            'uri' => $route->uri(),
            'methods' => $route->methods(),
            'name' => $route->getName(),
        ];
    });
    
    return response()->json([
        'panels_count' => count($panels),
        'panels' => array_keys($panels),
        'admin_routes' => $routes,
        'filament_installed' => class_exists(\Filament\Panel::class),
        'provider_exists' => class_exists(\App\Providers\Filament\AdminPanelProvider::class),
    ]);
});

// Toutes les autres routes sont gérées par Filament
// Filament utilise automatiquement le préfixe /admin