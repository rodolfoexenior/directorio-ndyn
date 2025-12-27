<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminRole; // <-- Importación del Middleware

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        // Registro del alias 'admin' para Route::middleware('admin')
        $middleware->alias([
            'admin' => AdminRole::class, // <-- Alias registrado
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();