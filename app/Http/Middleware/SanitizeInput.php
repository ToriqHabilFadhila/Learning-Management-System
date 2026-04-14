<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    public function handle(Request $request, Closure $next): Response
    {
        // Sanitize all input
        $this->sanitize($request);

        return $next($request);
    }

    protected function sanitize(Request $request)
    {
        $input = $request->all();

        foreach ($input as $key => $value) {
            if (is_string($value)) {
                // Remove dangerous HTML tags
                $value = strip_tags($value, '<b><i><u><strong><em><p><br><a><ul><ol><li>');
                
                // Escape HTML entities
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                
                // Remove null bytes
                $value = str_replace("\0", '', $value);
                
                $input[$key] = $value;
            } elseif (is_array($value)) {
                $input[$key] = $this->sanitizeArray($value);
            }
        }

        $request->merge($input);
    }

    protected function sanitizeArray(array $array): array
    {
        foreach ($array as $key => $value) {
            if (is_string($value)) {
                $value = strip_tags($value, '<b><i><u><strong><em><p><br><a><ul><ol><li>');
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                $value = str_replace("\0", '', $value);
                $array[$key] = $value;
            } elseif (is_array($value)) {
                $array[$key] = $this->sanitizeArray($value);
            }
        }

        return $array;
    }
}
