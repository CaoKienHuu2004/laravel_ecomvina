<?php

namespace App\Http\Resources\Toi\TheoDoiDonHangDetail;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="DiaChiGiaoHangResource",
 *     type="object",
 *     title="Địa chỉ giao hàng",
 *
 *     @OA\Property(property="id", type="integer", example=17),
 *     @OA\Property(property="id_nguoidung", type="integer", example=20),
 *     @OA\Property(property="hoten", type="string", example="lee huy"),
 *     @OA\Property(property="sodienthoai", type="string", example="0983914069"),
 *     @OA\Property(property="diachi", type="string", example="175/19 đường số 20 Gò Vấp"),
 *     @OA\Property(property="tinhthanh", type="string", example="Thành phố Hồ Chí Minh"),
 *     @OA\Property(property="trangthai", type="string", example="Khác")
 * )
 */
class DiaChiGiaoHangResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
