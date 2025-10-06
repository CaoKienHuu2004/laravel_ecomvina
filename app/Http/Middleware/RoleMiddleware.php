<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;


class RoleMiddleware
{
    use ApiResponse;
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            // chưa login
            if ($request->expectsJson() || $request->is('api/*')) {
                return $this->jsonResponse(['message' => 'Unauthorized'], 401);
            }
            return redirect()->route('login');
        }

        // check role
        if (!in_array($request->user()->vaitro, $roles)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return $this->jsonResponse(['message' => 'Forbidden - Bạn không có quyền truy cập'], 403);
            }
            // Đã login nhưng sai role → luôn về login
            return redirect()->route('test-guest')
                ->with('error', 'Bạn không có quyền truy cập.');
            // return redirect('/')
            //     ->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}
