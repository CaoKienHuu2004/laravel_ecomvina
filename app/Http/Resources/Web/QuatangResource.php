<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;
use App\Models\GiohangModel;

class QuatangResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $today = Carbon::today();
        $endDate = Carbon::parse($this->ngayketthuc);
        $thoigian_conlai = $endDate->gte($today) ? $today->diffInDays($endDate) : 0;

        // -----------------------------------------
        //   XÁC ĐỊNH NGƯỜI DÙNG: TOKEN hoặc SESSION
        // -----------------------------------------
        $token = $request->bearerToken();
        $cartTotal = 0;

        if ($token) {
            /** USER ĐÃ ĐĂNG NHẬP → kích hoạt token */
            $redisKey = "api_token:$token";
            $userId = Redis::get($redisKey);

            if ($userId) {
                // lấy tổng tiền giỏ hàng từ DB
                $cartTotal = GiohangModel::where('id_nguoidung', $userId)
                    ->sum('thanhtien');
            }

        } else {
            /** USER KHÔNG ĐĂNG NHẬP → giỏ hàng session */
            $cart_session = config('cart_session.session_key_cart', 'cart_session');
            $sessionCart = $request->session()->get($cart_session, []);

            // tính tổng tiền session
            $cartTotal = collect($sessionCart)
                ->where('thanhtien', '>', 0)     // loại bỏ quà tặng (thanhtien = 0)
                ->sum('thanhtien');
        }

        // -----------------------------------------
        //   TÍNH % ĐẠT ĐƯỢC
        // -----------------------------------------
        $phanTramDatDuoc = 0;

        if ($this->dieukiengiatri > 0) {
            $phanTramDatDuoc = round(($cartTotal / $this->dieukiengiatri) * 100);
            $phanTramDatDuoc = min(100, max(0, $phanTramDatDuoc));
        }

        return [
            'id' => $this->id,
            'id_bienthe' => $this->id_bienthe,
            'id_chuongtrinh' => $this->id_chuongtrinh,
            'thongtin_thuonghieu' => [
                'id_thuonghieu' => $this->bienthe->sanpham->thuonghieu->id,
                'ten_thuonghieu' => $this->bienthe->sanpham->thuonghieu->ten,
                'slug_thuonghieu' => $this->bienthe->sanpham->thuonghieu->slug,
                'logo_thuonghieu' => $this->bienthe->sanpham->thuonghieu->logo,
            ],
            'dieukiensoluong' => $this->dieukiensoluong,
            'dieukiengiatri' => $this->dieukiengiatri,
            'phantram_datduoc' => $phanTramDatDuoc,
            'tieude' => $this->tieude,
            'thongtin' => $this->thongtin,
            'hinhanh' => $this->hinhanh,
            'luotxem' => $this->luotxem,
            'ngaybatdau' => $this->ngaybatdau,
            'thoigian_conlai' => $thoigian_conlai,
            'ngayketthuc' => $this->ngayketthuc,
            'trangthai' => $this->trangthai,
            'bienthe_quatang' => [
                'ten_bienthe_quatang' => $this->bienthe->sanpham->ten,
                'ten_loaibienthe_quatang' => $this->bienthe->loaibienthe->ten,
                'slug_bienthe_quatang_sanpham' => $this->bienthe->sanpham->slug,
                'hinhanh' => $this->bienthe->sanpham->hinhanhsanpham()->orderBy('id', 'desc')->first()->hinhanh ?? null,
                'soluong' => 1,
            ]
        ];
    }
}
