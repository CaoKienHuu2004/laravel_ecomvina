<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GioHangResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Kiểm tra user hiện tại có role là admin không
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';

        return [
            'id'           => $this->id,
            'soluong'      => $this->soluong,
            'tongtien'     => $this->tongtien,
            'id_sanpham'   => $this->id_sanpham,
            'id_nguoidung' => $this->id_nguoidung,

            // Chỉ admin mới xem được created_at và updated_at
            'created_at'   => $isAdmin ? $this->created_at?->format('d-m-Y H:i:s') : null,
            'updated_at'   => $isAdmin ? $this->updated_at?->format('d-m-Y H:i:s') : null,

            // Load quan hệ khi cần
            'nguoidung'    => new NguoidungResources($this->whenLoaded('nguoidung')),
            'sanpham'      => new SanphamResources($this->whenLoaded('sanpham')),
        ];
    }
}
