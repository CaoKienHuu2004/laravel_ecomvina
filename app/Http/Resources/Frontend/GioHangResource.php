<?php

namespace App\Http\Resources\Frontend;

use App\Http\Resources\ChiTietGioHangResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GioHangResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'id_nguoidung' => $this->id_nguoidung,

            // unwrap user (nếu có quan hệ belongsTo User)
            'nguoidung'    => $this->whenLoaded('nguoidung', function () use ($request) {
                return $this->nguoidung->toArray();
            }),

            // danh sách chi tiết giỏ hàng
            'chitiet'     => ChiTietGioHangResource::collection($this->whenLoaded('chitiet')),
        ];
    }
}
