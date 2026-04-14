<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Force HTTPS in production
        if (app()->environment('production')) {
            $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Prevent clickjacking
        $response->header('X-Frame-Options', 'DENY');

        // Prevent MIME type sniffing
        $response->header('X-Content-Type-Options', 'nosniff');

        // Enable XSS protection
        $response->header('X-XSS-Protection', '1; mode=block');

        // Content Security Policy
        $viteUrl = app()->environment('local') ? "http://localhost:5173 http://127.0.0.1:5173" : "";
        $response->header('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net unpkg.com https://www.gstatic.com {$viteUrl}; style-src 'self' 'unsafe-inline' cdn.jsdelivr.net https://fonts.googleapis.com {$viteUrl}; img-src 'self' data: https:; font-src 'self' cdn.jsdelivr.net https://fonts.gstatic.com http://127.0.0.1:5173; connect-src 'self' https: ws: wss: {$viteUrl};");


        // Referrer Policy
        $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy
        $response->header('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Remove server header
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');

        return $response;
    }
}
