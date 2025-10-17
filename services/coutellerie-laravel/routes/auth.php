<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Routes d'authentification simples pour Laravel
// Filament gère l'authentification sur /admin

Route::middleware('guest')->group(function () {
    Route::get('login', function () {
        return redirect('/admin');
    })->name('login');
    
    Route::get('register', function () {
        return redirect('/admin');
    })->name('register');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', function () {
        Auth::logout();
        return redirect('/admin');
    })->name('logout');
});