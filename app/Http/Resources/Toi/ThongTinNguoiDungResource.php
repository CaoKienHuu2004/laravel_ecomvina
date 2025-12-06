<?php

namespace App\Http\Resources\Toi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThongTinNguoiDungResource extends JsonResource
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
            // 'username' => $this->username,
            'username' => $this->username,
            'email' => $this->email,
            'sodienthoai' => $this->sodienthoai,
            'hoten' => $this->hoten,
            'gioitinh' => $this->gioitinh,
            'ngaysinh' => $this->ngaysinh ? $this->ngaysinh->format('Y-m-d') : null,
            'avatar' => $this->avatar,
            // 'vaitro' => $this->vaitro,
            // 'trangthai' => $this->trangthai,
            // Thêm địa chỉ
            'diachi' => DiachiGiaohangResource::collection($this->whenLoaded('diachi')),
        ];
    }
}
