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
            'hinhanh' => $this->hinhanh,
            'trangthai' => $this->trangthai,
            // Tải có điều kiện các mối quan hệ
            // Sử dụng whenLoaded để tránh lỗi N+1 query
            'san_pham' => new SanphamResources($this->whenLoaded('sanpham')),
        ];
        // 'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
        if ($isAdmin) {
            $data['created_at'] = $this->created_at ? $this->created_at->toIso8601String() : null;
            $data['updated_at'] = $this->updated_at ? $this->updated_at->toIso8601String() : null;
            $data['deleted_at'] = $this->deleted_at ? $this->deleted_at->toIso8601String() : null;
        }
        return $data;
    }
}
