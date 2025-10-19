<?php

use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {
        // Configurer le middleware CSRF personnalisé
        $middleware->web(replace: [
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class => \App\Http\Middleware\VerifyCsrfToken::class,
        ]);
    })
    ->withExceptions(function ($exceptions) {
        // Gestion personnalisée de l'authentification pour Filament
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            // Pour les requêtes API, retourner du JSON
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 401);
            }

            // Pour les routes Filament admin
            if ($request->is('admin') || $request->is('admin/*')) {
                return redirect()->guest('/admin/login');
            }

            // Pour les autres routes, fallback vers /login
            return redirect()->guest('/login');
        });
    })
    ->withProviders([
        // Enregistrer les providers essentiels
        \App\Providers\AppServiceProvider::class,
        // Enregistrer le fournisseur de panneau d'administration Filament
        \App\Providers\Filament\AdminPanelProvider::class,
    ])
    ->create();
