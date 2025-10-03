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

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     * Usage: ->middleware(['auth:sanctum','role:admin'])
     * hoặc   ->middleware(['auth:sanctum','role:admin,user'])
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return $this->jsonResponse(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }
        $user = $request->user();

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
        // return $this->jsonResponse([ 'message' => 'Forbidden - Bạn không có quyền truy cập' ], Response::HTTP_FORBIDDEN);


        // sai role
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->jsonResponse([
                'message' => 'Forbidden - Bạn không có quyền truy cập'
            ], Response::HTTP_FORBIDDEN);

        }
        return response()->view('errors.403', [], Response::HTTP_FORBIDDEN);
    }
}
// namespace App\Http\Middleware;

// use App\Traits\ApiResponse;
// use Closure;
// use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;

// class EnsureUserHasRole
// {
//     use ApiResponse;
//     /**
//      * Handle an incoming request.
//      * Usage: ->middleware(['auth:sanctum','role:admin'])
//      * hoặc   ->middleware(['auth:sanctum','role:admin,user'])
//      */
//     public function handle(Request $request, Closure $next, ...$roles): Response
//     {
//         $user = $request->user();

//         if (!$user) {
//             // Web hay API trả 401 khác nhau
//             if ($request->expectsJson()) {
//                 return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
//             }
//             return redirect('/login');
//         }

//         // Kiểm tra role
//         $roleValue = $user->vaitro ?? null; // hoặc 'role' tùy model
//         if (!in_array($roleValue, $roles)) {
//             if ($request->expectsJson()) {
//                 return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
//             }
//             // Web redirect về dashboard hoặc home
//             return redirect('/dashboard');
//         }

//         return $next($request);
//     }

// }
