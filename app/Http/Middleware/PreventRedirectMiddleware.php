<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventRedirectMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Si está tratando de redirigir, fuerza a JSON plano
        if ($response->isRedirection()) {
            return response()->json([
                'error' => 'No redirection allowed from API.',
                'redirect_url' => $response->headers->get('Location'),
            ], 403);
        }

        return $response;
    }
}

