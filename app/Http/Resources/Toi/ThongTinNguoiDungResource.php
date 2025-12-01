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
            'username' => $this->username,
            'sodienthoai' => $this->sodienthoai,
            'hoten' => $this->hoten,
            'gioitinh' => $this->gioitinh,
            'ngaysinh' => $this->ngaysinh,
            'avatar' => $this->avatar,
            'trangthai' => $this->trangthai,
        ];
    }
}
