<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyEsp32Token
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-ESP32-Token') ?? $request->bearerToken();
        $validToken = env('ESP32_API_TOKEN');

        if (!$validToken || $token !== $validToken) {
            return response()->json(['error' => 'No autorizado. Token de ESP32 inválido.'], 401);
        }

        return $next($request);
    }
}
