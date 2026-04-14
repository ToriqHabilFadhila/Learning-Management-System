<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitRequests
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next): Response
    {
        // Rate limit login attempts
        if ($request->is('login') && $request->isMethod('post')) {
            $key = 'login_attempts:' . $request->ip();
            
            if ($this->limiter->tooManyAttempts($key, 5, 15)) {
                return response()->json([
                    'message' => 'Terlalu banyak percobaan login. Coba lagi dalam 15 menit.'
                ], 429);
            }

            $this->limiter->hit($key, 15 * 60);
        }

        // Rate limit registration
        if ($request->is('register') && $request->isMethod('post')) {
            $key = 'register_attempts:' . $request->ip();
            
            if ($this->limiter->tooManyAttempts($key, 3, 60)) {
                return response()->json([
                    'message' => 'Terlalu banyak percobaan registrasi. Coba lagi dalam 1 jam.'
                ], 429);
            }

            $this->limiter->hit($key, 60 * 60);
        }

        // Rate limit password reset
        if ($request->is('forgot-password') && $request->isMethod('post')) {
            $key = 'password_reset:' . $request->ip();
            
            if ($this->limiter->tooManyAttempts($key, 3, 60)) {
                return response()->json([
                    'message' => 'Terlalu banyak percobaan reset password. Coba lagi dalam 1 jam.'
                ], 429);
            }

            $this->limiter->hit($key, 60 * 60);
        }

        return $next($request);
    }
}
