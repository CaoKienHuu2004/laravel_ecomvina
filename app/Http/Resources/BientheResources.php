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
        $isAdmin = $request->user()?->isAdmin() ?? false;
        $data = [
        'id' => $this->id,
        'gia' => $this->gia,
        'so_luong' => $this->soluong,
        'uu_tien' => $this->uutien,
        // Tải có điều kiện các mối quan hệ
        // Sử dụng whenLoaded để tránh lỗi N+1 query
        'loai_bien_the' => new LoaibientheResources($this->whenLoaded('loaiBienThe')),
        'san_phams' => new SanphamResources($this->whenLoaded('sanPham')),

        ];
        if ($isAdmin) {
            $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
            $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
            $data['deleted_at'] = $this->deleted_at?->format('d-m-Y H:i:s');
        }
        return $data;
    }
}
