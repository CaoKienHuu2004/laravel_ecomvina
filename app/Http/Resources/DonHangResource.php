<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonHangResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()?->isAdmin() ?? false;

        return [
            'id'           => $this->id,
            'ma_donhang'   => $this->ma_donhang,
            'tongtien'     => $this->tongtien,
            'tongsoluong'  => $this->tongsoluong,
            'ghichu'       => $this->ghichu,
            'ngaytao'      => $this->ngaytao?->format('d-m-Y H:i:s'),
            'trangthai'    => $this->trangthai,

            // Chỉ admin mới thấy thông tin người dùng và mã giảm giá
            'nguoidung'    => $isAdmin ? new NguoidungResources($this->whenLoaded('nguoidung')) : null,
            'magiamgia'    => $isAdmin ? new MaGiamGiaResource($this->whenLoaded('magiamgia')) : null,
        ];
    }
}
