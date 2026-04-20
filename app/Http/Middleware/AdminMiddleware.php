<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Ce middleware vérifie si l'utilisateur est admin
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 🔐 Vérifie si connecté + admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {

            // ❌ Bloque accès si pas admin
            abort(403, 'Accès refusé - Admin uniquement');
        }

        // ✅ Autorise l'accès
        return $next($request);
    }
}