<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Importa la facade Log

class Authenticate
{
    /**
     * Manejar una solicitud entrante.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Si es una ruta API, usa el guardia 'sanctum'
        if ($request->is('api/*')) {
            Auth::shouldUse('sanctum');
        }
    
        if (!Auth::check()) {
            Log::warning('Usuario no autenticado', [
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
    
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }
    
            return redirect('/login');
        }
    
        Log::info('Usuario autenticado', [
            'user_id' => Auth::id(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    
        return $next($request);
    }
}