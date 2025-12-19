<?php

namespace App\Http\Resources\Toi\TheoDoiDonHangDetail;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="PhuongThucThanhToanResource",
 *     type="object",
 *     title="Phương thức thanh toán",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="ten", type="string", example="Chuyển khoản ngân hàng trực tiếp"),
 *     @OA\Property(property="maphuongthuc", type="string", example="dbt"),
 *     @OA\Property(property="trangthai", type="string", example="Hoạt động")
 * )
 */
class PhuongThucResource extends JsonResource
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
