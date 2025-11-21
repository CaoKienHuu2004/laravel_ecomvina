<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\NguoidungModel;
use App\Models\DonhangModel;
use App\Traits\ApiResponse;

class AuthUsernameOrderMiddleware
{
    use ApiResponse;

    public function handle(Request $request, Closure $next)
    {
        $onlyUsername = $request->input('username');
        $onlyEmail    = $request->input('email');
        $madonhang    = $request->input('madon');

        if ((!$onlyEmail && !$onlyUsername) || !$madonhang) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Thiếu thông tin để xác thực đơn hàng!',
            ], 401);
        }

        // ----- TÌM USER THEO USERNAME HOẶC EMAIL -----
        $query = NguoidungModel::query();

        $query->when($onlyEmail, function($q) use ($onlyEmail) {
            $q->whereRaw("SUBSTRING_INDEX(username, ',', -1) = ?", [$onlyEmail]);
        });

        $query->when($onlyUsername, function($q) use ($onlyUsername) {
            $q->orWhereRaw("SUBSTRING_INDEX(username, ',', 1) = ?", [$onlyUsername]);
        });
        $user = $query->first();

        if (!$user) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Tên tài khoản hoặc email không tồn tại!',
            ], 401);
        }

        // ----- TÌM ĐƠN HÀNG -----
        $donhang = DonhangModel::where('madon', $madonhang)
            ->where('id_nguoidung', $user->id)
            ->first();

        if (!$donhang) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Thiếu tên tài khoản hoặc mã đơn hàng để xác thực!',
            ], 403);
        }

        // Gán vào request
        $request->attributes->set('auth_user', $user);
        $request->attributes->set('auth_donhang', $donhang);

        return $next($request);
    }
}
