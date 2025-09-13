<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BientheResources extends JsonResource
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
        'gia' => $this->gia,
        'so_luong' => $this->soluong,
        'uu_tien' => $this->uutien,
        
        // Tải có điều kiện các mối quan hệ
        // Sử dụng whenLoaded để tránh lỗi N+1 query
        'loai_bien_the' => new LoaibientheResources($this->whenLoaded('loaiBienThe')),
        
    ];
    }
}
