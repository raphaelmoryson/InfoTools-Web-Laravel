<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CommercialMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Si l'utilisateur n'est pas connecté
        if (!auth()->check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        // Si l'utilisateur n'est pas commercial
        if (!auth()->user()->is_commercial) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
