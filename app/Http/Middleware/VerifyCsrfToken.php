<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            // Redirect back with error message for CSRF token mismatch
            return redirect()->back()->withErrors([
                'csrf' => 'Sesi Anda telah berakhir. Silakan refresh halaman dan coba lagi.'
            ])->withInput($request->except('password', 'password_confirmation'));
        }
    }
}