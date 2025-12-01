<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\NguoidungModel;
use App\Models\DonHangModel;
use App\Traits\ApiResponse;

class AuthUsernameOrderMiddleware
{
    use ApiResponse;

    public function handle(Request $request, Closure $next)
    {
        $username = $request->input('username');
        $madonhang = $request->input('madon');

        if (!$username || !$madonhang) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Thiếu tên tài khoản hoặc mã đơn hàng để xác thực!',
            ], 401);
        }

        $user = NguoidungModel::where('username', $username)->first();
        if (!$user) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Thiếu tên tài khoản hoặc mã đơn hàng để xác thực!',
            ], 401);
        }

        $donhang = DonHangModel::where('madon', $madonhang)
                    ->where('id_nguoidung', $user->id)
                    ->first();

        if (!$donhang) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Thiếu tên tài khoản hoặc mã đơn hàng để xác thực!',
            ], 403);
        }

        $request->attributes->set('auth_user', $user);
        $request->attributes->set('auth_donhang', $donhang);

        return $next($request);
    }
}
