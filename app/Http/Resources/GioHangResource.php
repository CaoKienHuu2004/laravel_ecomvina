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
        $data = [
            'id'           => $this->id,
            'soluong'      => $this->soluong,
            'tongtien'     => $this->tongtien,
            'id_sanpham'   => $this->id_sanpham,
            'id_nguoidung' => $this->id_nguoidung,
            // Load quan hệ khi cần
            'nguoidung'    => new NguoidungResources($this->whenLoaded('nguoidung')),
            'bienthesp'      => new BientheResources($this->whenLoaded('bienthesp')),
        ];
        if ($isAdmin) {
            $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
            $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
            $data['deleted_at'] = $this->deleted_at?->format('d-m-Y H:i:s');
        }
        return $data;
    }
}
