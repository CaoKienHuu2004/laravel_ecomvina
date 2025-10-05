<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SanphamResources extends JsonResource
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
        'ten_san_pham' => $this->ten,
        'mo_ta' => $this->mota,
        'xuat_xu' => $this->xuatxu,
        'san_xuat' => $this->sanxuat,
        'url_video' => $this->mediaurl,
        'luot_xem' => $this->luotxem,
        'ngay_cap_nhat'=> $this->updated_at->format('d-m-Y H:i:s'),
        
        // Tải có điều kiện các mối quan hệ
        // Sử dụng whenLoaded để tránh lỗi N+1 query
        'thuonghieus' => new ThuonghieuResources($this->whenLoaded('thuonghieu')),
        'danhmucs' => DanhmucResources::collection($this->whenLoaded('danhmuc')),
        'bienthes' => BientheResources::collection($this->whenLoaded('bienThe')),
        'anhsanphams' => AnhsanphamResources::collection($this->whenLoaded('anhSanPham')),
    ];
    }
}
