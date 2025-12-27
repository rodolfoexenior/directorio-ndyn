<?php

namespace App\Http\Middleware; // <-- ¡ESTA LÍNEA FALTABA!

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado Y si tiene el rol 'admin_principal'
        if (Auth::check() && Auth::user()->hasRole('admin_principal')) {
            return $next($request);
        }

        // Si el usuario no es admin_principal, lo redirigimos o le mostramos un 403.
        abort(403, 'Acceso Denegado. Se requiere el rol de Administrador Principal.');
    }
}