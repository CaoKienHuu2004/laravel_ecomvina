<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaGiamGiaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()?->isAdmin() ?? false;

        return [
            'id'           => $this->id,
            'magiamgia'    => $this->magiamgia,
            'mota'         => $this->mota,
            'giatri'       => $this->giatri,
            'dieukien'     => $this->dieukien,
            'ngaybatdau'   => $this->ngaybatdau?->format('d-m-Y'),
            'ngayketthuc'  => $this->ngayketthuc?->format('d-m-Y'),
            'trangthai'    => $this->trangthai,

            // Chỉ admin mới thấy các đơn hàng dùng mã này
            'donhangs'     => $isAdmin ? DonHangResource::collection($this->whenLoaded('donHang')) : null,
        ];
    }
}
