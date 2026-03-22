<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Gestionar la petición entrante y verificar si el usuario es administrador
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si no está logueado o no tiene el rol de admin, le denegamos el acceso
        if (!auth()->check() || auth()->user()->rol !== 'admin') {
            return response()->json(['message' => 'Acción no autorizada.'], 403);
        }

        return $next($request);
    }
}
