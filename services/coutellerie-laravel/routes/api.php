<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KnifeController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\AuthController;

// Routes publiques
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'timestamp' => now()->toISOString(),
        'service' => 'Coutellerie Laravel API'
    ]);
});

// Routes API pour les couteaux (publiques)
Route::prefix('knives')->group(function () {
    Route::get('/', [KnifeController::class, 'index']);
    Route::get('/categories', [KnifeController::class, 'categories']);
    Route::get('/{id}', [KnifeController::class, 'show']);
});

// Routes d'authentification
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::middleware('auth:sanctum')->post('/verify', [AuthController::class, 'verify']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
});

// Route pour les messages de contact (publique)
Route::post('/contact', [ContactController::class, 'store'])
    ->withoutMiddleware(['csrf'])
    ->middleware('throttle:5,1'); // 5 tentatives par minute

// Routes protégées pour l'administration
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('admin')->group(function () {
        // CRUD des couteaux pour l'admin
        Route::post('/knives', [KnifeController::class, 'store']);
        Route::put('/knives/{id}', [KnifeController::class, 'update']);
        Route::delete('/knives/{id}', [KnifeController::class, 'destroy']);
    });
});
