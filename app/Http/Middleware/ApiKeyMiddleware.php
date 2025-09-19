<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Lấy api key từ header
        $apiKey = $request->header('X-API-KEY');

        // So sánh với key trong .env
        if ($apiKey !== config('app.api_key')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
