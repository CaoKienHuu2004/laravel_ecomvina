<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra nếu chưa login
        if (!Auth::check()) {
            return redirect()->route('dang-nhap');
        }

        // Kiểm tra role
        if (Auth::user()->vaitro !== 'admin') {
            abort(403, 'Bạn không có quyền truy cập'); // hoặc redirect về trang khác
        }

        return $next($request);
    }
}
