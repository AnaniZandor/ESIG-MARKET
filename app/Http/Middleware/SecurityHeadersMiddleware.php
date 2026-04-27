<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     * Ajoute des en-têtes de sécurité HTTP
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // ✅ Empêche le chargement du site dans un iframe (anti-clickjacking)
        $response->headers->set('X-Frame-Options', 'DENY');
        
        // ✅ Empêche le MIME type sniffing (anti-XSS)
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // ✅ Active la protection XSS du navigateur
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // ✅ Contrôle la quantité d'informations dans le Referer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // ✅ Empêche les navigateurs de deviner le contenu
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
        
        return $response;
    }
}