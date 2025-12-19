<?php

namespace App\Http\Resources\Toi\TheoDoiDonHangDetail;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="PhiVanChuyenResource",
 *     type="object",
 *     title="Phí vận chuyển",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="ten", type="string", example="Nội thành"),
 *     @OA\Property(property="phi", type="integer", example=25000),
 *     @OA\Property(property="trangthai", type="string", example="hiển thị")
 * )
 */
class PhiVanChuyenResource extends JsonResource
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
