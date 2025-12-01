<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
/**
 * @OA\Schema(
 *     schema="TinhThanhVietNam",
 *     type="object",
 *     title="TinhThanhVietNam",
 *     description="Thông tin tỉnh/thành Việt Nam",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="ten", type="string", example="TP. Hà Nội"),
 *     @OA\Property(property="code", type="string", example="HN"),
 *     @OA\Property(property="khuvuc", type="string", example="Đồng bằng Sông Hồng"),
 * )
 */
class TinhThanhVietNamResource extends JsonResource
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
