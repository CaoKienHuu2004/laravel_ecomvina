<?php

namespace App\Http\Resources\Toi\TheoDoiDonHang;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Toi\TheoDoiDonHang\BienTheResource;


/**
 * @OA\Schema(
 *     schema="ChiTietDonHangResource",
 *     type="object",
 *     title="ChiTietDonHangResource",
 *     description="Thông tin chi tiết của từng sản phẩm trong đơn hàng",
 *     @OA\Property(property="id", type="integer", example=101, description="ID chi tiết đơn hàng"),
 *     @OA\Property(property="soluong", type="integer", example=2, description="Số lượng sản phẩm trong chi tiết đơn hàng"),
 *     @OA\Property(property="dongia", type="number", format="float", example=150000, description="Đơn giá của sản phẩm tại thời điểm mua"),
 *     @OA\Property(property="tong_tien", type="number", format="float", example=300000, description="Tổng tiền (số lượng x đơn giá) của chi tiết đơn hàng"),
 *     @OA\Property(
 *         property="bienthe",
 *         type="object",
 *         description="Thông tin biến thể của sản phẩm",
 *         ref="#/components/schemas/BienTheResource"
 *     )
 * )
 */
class ChiTietDonHangResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'soluong' => $this->soluong,
            'dongia' => $this->dongia,
            // 'trangthai' => $this->trangthai,
            'tong_tien' => $this->soluong * $this->dongia,
            'bienthe' => new BienTheResource($this->bienthe),
        ];
    }
}
