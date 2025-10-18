<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KnifeController;
use App\Http\Controllers\Api\ContactController;

// Routes publiques
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'timestamp' => date('c'),
        'service' => 'Coutellerie Laravel API'
    ]);
});

// Route de debug pour vérifier la configuration CORS et headers
Route::get('/debug', function (Request $request) {
    return response()->json([
        'status' => 'Debug API',
        'headers' => [
            'origin' => $request->header('Origin'),
            'user-agent' => $request->header('User-Agent'),
            'host' => $request->header('Host'),
            'referer' => $request->header('Referer'),
        ],
        'cors_config' => [
            'allowed_origins' => config('cors.allowed_origins'),
            'allowed_headers' => config('cors.allowed_headers'),
        ],
        'sanctum_config' => [
            'stateful_domains' => config('sanctum.stateful'),
        ],
        'environment' => [
            'app_url' => env('APP_URL'),
            'frontend_url' => env('FRONTEND_URL'),
        ],
    ]);
});

// Routes API pour les couteaux
Route::prefix('knives')->group(function () {
    Route::get('/', [KnifeController::class, 'index']);
    Route::get('/categories', [KnifeController::class, 'categories']);
    Route::get('/{id}', [KnifeController::class, 'show']);
});

// Route pour les messages de contact (publique)
Route::post('/contact', [ContactController::class, 'store'])
    ->withoutMiddleware(['csrf'])
    ->middleware('throttle:5,1'); // 5 tentatives par minute

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::apiResource('knives', KnifeController::class)->except(['index', 'show']);
    });
});
