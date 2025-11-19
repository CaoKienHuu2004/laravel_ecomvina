<?php

namespace App\Http\Resources\Toi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="DiachiGiaohangResource",
 *     type="object",
 *     title="Địa chỉ giao hàng",
 *     @OA\Property(property="id", type="integer", example=1, description="ID địa chỉ"),
 *     @OA\Property(property="hoten", type="string", example="Nguyễn Văn A", description="Họ tên người nhận"),
 *     @OA\Property(property="sodienthoai", type="string", example="0987654321", description="Số điện thoại"),
 *     @OA\Property(property="diachi", type="string", example="123 Đường ABC, Quận XYZ", description="Địa chỉ chi tiết"),
 *     @OA\Property(property="tinhthanh", type="string", example="Thành Phố Hà Nội", description="Tỉnh thành"),
 *     @OA\Property(property="trangthai", type="string", example="Mặc định", description="Trạng thái địa chỉ"),
 * )
 */
class DiachiGiaohangResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hoten' => $this->hoten,
            'sodienthoai' => $this->sodienthoai,
            'diachi' => $this->diachi,
            'tinhthanh' => $this->tinhthanh,
            'trangthai' => $this->trangthai,
        ];
    }
}
