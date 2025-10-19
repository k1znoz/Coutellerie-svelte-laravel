<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
public function register(): void
{
    $this->reportable(function (Throwable $e) {
        //
    });
}

protected function unauthenticated($request, AuthenticationException $exception)
{
    // Si c'est une requête JSON (API), retourner JSON
    if ($request->expectsJson()) {
        return response()->json(['message' => $exception->getMessage()], 401);
    }
    
    // Si c'est une requête sur admin, rediriger vers la page de login admin
    if ($request->is('admin') || $request->is('admin/*')) {
        return redirect('/admin');
    }
    
    // Sinon, rediriger vers login général
    return redirect()->guest(route('login'));
}


}