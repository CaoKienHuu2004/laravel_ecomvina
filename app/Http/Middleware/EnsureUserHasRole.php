<?php

// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;

// class EnsureUserHasRole
// {
//     /**
//      * Handle an incoming request.
//      * Usage: ->middleware(['auth:sanctum','role:admin'])
//      */
//     public function handle(Request $request, Closure $next, string $role): Response
//     {
//         $user = $request->user();

//         if (!$user || !method_exists($user, 'hasRole') || !$user->hasRole($role)) {
//             return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
//         }

//         return $next($request);
//     }
// }

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware(['auth:sanctum','role:admin'])
     * hoặc   ->middleware(['auth:sanctum','role:admin,user'])
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        // Nếu model User (hoặc NguoiDung) có hàm hasRole() thì dùng
        if (method_exists($user, 'hasRole')) {
            foreach ($roles as $role) {
                if ($user->hasRole($role)) {
                    return $next($request);
                }
            }
        } else {
            // Nếu không có hasRole(), kiểm tra trực tiếp cột "vaitro"
            if (in_array($user->vaitro, $roles)) {
                return $next($request);
            }
        }

        return response()->json([
            'message' => 'Forbidden - Bạn không có quyền truy cập'
        ], Response::HTTP_FORBIDDEN);
    }
}

