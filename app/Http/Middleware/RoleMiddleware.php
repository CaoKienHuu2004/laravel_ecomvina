<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$request->user() || $request->user()->vaitro !== $role) { // trong Model đang dung vaitro thay vì role
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $next($request);
    }
}
