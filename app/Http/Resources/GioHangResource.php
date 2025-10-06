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
            'tongtien'     => $this->tongtien,
            // Load quan hệ khi cần
            'nguoidung' => new NguoiDungResources($this->whenLoaded('nguoidung')),
            'chitiet'   => ChiTietGioHangResource::collection($this->whenLoaded('chitiet')),
        ];
        if ($isAdmin) {
            $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
            $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
            $data['deleted_at'] = $this->deleted_at?->format('d-m-Y H:i:s');
        }
        return $data;
    }
}
