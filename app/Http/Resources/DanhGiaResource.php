<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DanhGiaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()?->isAdmin() ?? false;

        return [
            'id'        => $this->id,
            'diem'      => $this->diem,
            'noidung'   => $this->noidung,
            'media'     => $this->media,
            'ngaydang'  => $this->ngaydang?->format('d-m-Y'),
            'trangthai' => $this->trangthai,

            // Chỉ admin mới thấy thông tin người dùng
            'nguoidung' => $isAdmin ? new NguoidungResources($this->whenLoaded('nguoidung')) : null,
            'sanpham'   => new SanphamResources($this->whenLoaded('sanpham')),
        ];
    }
}
