<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="MaGiamGia",
 *     type="object",
 *     title="Mã giảm giá",
 *     description="Thông tin chi tiết của mã giảm giá",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="magiamgia", type="string", example="SALE50"),
 *     @OA\Property(property="mota", type="string", example="Giảm 50% cho đơn hàng đầu tiên"),
 *     @OA\Property(property="trangthai", type="string", example="Hoạt động"),
 *     @OA\Property(property="giatri", type="number", format="float", example=50.0),
 *     @OA\Property(property="ngaybatdau", type="string", format="date", example="2025-01-01"),
 *     @OA\Property(property="ngayketthuc", type="string", format="date", example="2025-12-31"),
 *
 * )
 */
class MaGiamGiaResource extends JsonResource
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
