<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SukienKhuyenMaiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'khuyenmai'  => new QuatangKhuyenMaiResource($this->whenLoaded('khuyenmai')),
            'sukien'     => new ChuongTrinhSuKienResource($this->whenLoaded('sukien')),
        ];
    }
}
