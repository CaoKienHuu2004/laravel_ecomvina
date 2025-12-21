<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\NguoidungModel;
use App\Traits\ApiResponse;

class ApiAuthMiddleware
{
    use ApiResponse;
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('api/tai-khoan/donhangs/payment-callback')) {
            return $next($request);
        } // thêm vào để fix bug cực lạ là ko bọc trong middeleware auth.api mà vần bị chặn bới midlleware này
        if ($request->is('tai-khoan/donhang/payment-callback')) {
            return $next($request);
        } // thêm vào để fix bug cực lạ là ko bọc trong middeleware auth.api mà vần bị chặn bới midlleware này
        $token = $request->bearerToken();

        if (!$token) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Thiếu token xác thực!',
            ], 401);
        }

        // Kiểm tra token trong Redis
        $key = "api_token:$token";
        $userId = Redis::get($key);

        if (!$userId) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Token không hợp lệ hoặc đã hết hạn!',
            ], 401);
        }

        // Lấy thông tin user
        $user = NguoidungModel::find($userId);
        if (!$user) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Không tìm thấy người dùng tương ứng!',
            ], 401);
        }


        $request->attributes->set('auth_user', $user);

        return $next($request);
    }
}

