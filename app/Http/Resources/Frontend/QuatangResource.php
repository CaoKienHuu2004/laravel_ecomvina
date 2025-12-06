<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;

/**
 * @OA\Schema(
 *     schema="QuatangResource",
 *     type="object",
 *     title="QuatangResource",
 *
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="id_bienthe", type="integer"),
 *     @OA\Property(property="id_chuongtrinh", type="integer"),
 *
 *     @OA\Property(
 *         property="thongtin_thuonghieu",
 *         type="object",
 *         @OA\Property(property="id_thuonghieu", type="integer"),
 *         @OA\Property(property="ten_thuonghieu", type="string"),
 *         @OA\Property(property="slug_thuonghieu", type="string"),
 *         @OA\Property(property="logo_thuonghieu", type="string", format="url")
 *     ),
 *
 *     @OA\Property(property="dieukien", type="string"),
 *     @OA\Property(property="tieude", type="string"),
 *     @OA\Property(property="thongtin", type="string"),
 *     @OA\Property(property="hinhanh", type="string"),
 *     @OA\Property(property="luotxem", type="integer"),
 *
 *     @OA\Property(property="ngaybatdau", type="string", format="date"),
 *     @OA\Property(property="thoigian_conlai", type="integer", description="Số ngày còn lại"),
 *     @OA\Property(property="ngayketthuc", type="string", format="date"),
 *     @OA\Property(property="trangthai", type="string"),
 *
 *     @OA\Property(
 *         property="bienthe_quatang",
 *         type="object",
 *         @OA\Property(property="ten_bienthe_quatang", type="string"),
 *         @OA\Property(property="ten_loaibienthe_quatang", type="string"),
 *         @OA\Property(property="slug_bienthe_quatang_sanpham", type="string"),
 *         @OA\Property(property="hinhanh", type="string", nullable=true),
 *         @OA\Property(property="soluong", type="integer")
 *     )
 * )
 */
class QuatangResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        $today = Carbon::today();
        $endDate = Carbon::parse($this->ngayketthuc);
        $thoigian_conlai = $endDate->gte($today) ? $today->diffInDays($endDate) : 0; // Tính số ngày còn lại, nếu ngày kết thúc đã qua thì trả về 0


        $token = $request->bearerToken();
        $key = "api_token:$token";
        $userId = Redis::get($key); // user_id lấy từ redis

        $cartTotal = 0;

        if ($userId) {
            $cartTotal = \App\Models\GiohangModel::where('id_nguoidung', $userId)
                ->sum('thanhtien');
        }

        $phanTramDatDuoc = 0;

        if ($this->dieukiengiatri > 0) {
            $phanTramDatDuoc = round(($cartTotal / $this->dieukiengiatri) * 100);
            $phanTramDatDuoc = min(100, max(0, $phanTramDatDuoc)); // luôn 0–100%
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
                // 'ten_bienthe_quatang' => $this->bienthe->sanpham->ten.'-'.$this->bienthe->loaibienthe->ten,
                'ten_bienthe_quatang' => $this->bienthe->sanpham->ten,
                'ten_loaibienthe_quatang' => $this->bienthe->loaibienthe->ten,
                'slug_bienthe_quatang_sanpham' => $this->bienthe->sanpham->slug,
                'hinhanh' => $this->bienthe->sanpham->hinhanhsanpham()->orderBy('id', 'desc')->first()->hinhanh ?? null,
                'soluong' => 1, //11-27-25 tạm thời dưa có quản lý số lượng quà tăng
            ]
        ];
    }
}
