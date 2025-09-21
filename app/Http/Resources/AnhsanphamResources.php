<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnhsanphamResources extends JsonResource
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
            'media' => $this->media,
            'trangthai' => $this->trangthai,
            // Tải có điều kiện các mối quan hệ
            // Sử dụng whenLoaded để tránh lỗi N+1 query
            'san_pham' => new SanphamResources($this->whenLoaded('sanpham')),
        ];
        if ($isAdmin) {
            $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
            $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
            $data['deleted_at'] = $this->deleted_at?->format('d-m-Y H:i:s');
        }
        return $data;
    }
}
