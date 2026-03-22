<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use App\Http\Middleware\CheckAdmin;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Registro de alias para el middleware de administrador
        $middleware->alias([
            'admin' => CheckAdmin::class,
        ]);

        // Configuración de CORS para la API
        $middleware->api(prepend: [
            HandleCors::class,
        ]);
        
        // Habilitar el estado para autenticación basada en cookies/sesión
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Manejo de excepciones personalizadas si fuese necesario
    })->create();