<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommercialMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie si l'utilisateur est connecté ET s'il est commercial
        if ($request->user() && $request->user()->is_commercial) {
            return $next($request);
        }

        // Sinon, on bloque l'accès
        abort(403, 'Accès réservé aux commerciaux.');
    }
}