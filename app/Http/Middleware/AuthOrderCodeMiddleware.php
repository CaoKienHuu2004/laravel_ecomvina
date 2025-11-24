<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\NguoidungModel;
use App\Models\DonhangModel;
use App\Traits\ApiResponse;

class AuthOrderCodeMiddleware
{
    use ApiResponse;

    public function handle(Request $request, Closure $next)
    {
        $madonhang = $request->input('madon');

        if (!$madonhang) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Thiếu mã đơn hàng để xác thực!',
            ], 401);
        }

        // Tìm đơn hàng theo mã đơn
        $donhang = DonhangModel::where('madon', $madonhang)->first();

        if (!$donhang) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Mã đơn hàng không tồn tại!',
            ], 404);
        }

        // Lấy user của đơn hàng
        $user = NguoidungModel::find($donhang->id_nguoidung);
        if (!$user) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Người dùng của đơn hàng không tồn tại!',
            ], 404);
        }

        // Gán vào request để controller hoặc các bước tiếp theo sử dụng
        $request->attributes->set('auth_user', $user);
        $request->attributes->set('auth_donhang', $donhang);

        return $next($request);
    }
}
