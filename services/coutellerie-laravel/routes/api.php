<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KnifeController;
use App\Http\Controllers\Api\ContactController;

// Routes publiques
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'timestamp' => now()->toISOString(),
        'service' => 'Coutellerie Laravel API'
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
