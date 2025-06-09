<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventRedirectMiddleware
{
    /**
     * Middleware personalizado para evitar redirecciones no deseadas en las rutas de API.
     * Si se detecta una respuesta de redirección (por ejemplo, 302 a login),
     * se intercepta y se retorna un JSON con error 403.
     *
     * Esto es útil cuando el backend se usa como API REST sin sesiones de navegador.
     *
     * @param Request $request la solicitud HTTP entrante
     * @param Closure $next siguiente middleware o controlador
     * @return mixed respuesta JSON o la respuesta original
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Si la respuesta es una redirección (por ejemplo, a /login), la bloqueamos
        if ($response->isRedirection()) {
            return response()->json([
                'error' => 'No redirection allowed from API.',
                'redirect_url' => $response->headers->get('Location'), // Mostrar destino bloqueado
            ], 403);
        }

        // Si no es redirección, se deja pasar normalmente
        return $response;
    }
}
