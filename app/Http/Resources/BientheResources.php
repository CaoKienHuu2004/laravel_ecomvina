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
        'giagiam' => $this->giagiam,
        'soluong' => $this->soluong,
        'trangthai' => $this->trangthai,
        'uutien' => $this->uutien,
        // Tải có điều kiện các mối quan hệ
        // Sử dụng whenLoaded để tránh lỗi N+1 query
        'loaibienthe' => new LoaibientheResources($this->whenLoaded('loaibienthe')),
        'sanpham' => new SanphamResources($this->whenLoaded('sanpham')),

        ];
        if ($isAdmin) {
            $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
            $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
            $data['deleted_at'] = $this->deleted_at?->format('d-m-Y H:i:s');
        }
        return $data;
    }
}
