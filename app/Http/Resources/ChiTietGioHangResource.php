<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChiTietGioHangResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';
        $data = [
            'id'        => $this->id,
            'soluong'   => $this->soluong,
            'tongtien'  => $this->tongtien,
            // Quan hệ
            'giohang' => new GioHangResource($this->whenLoaded('gioHang')),
            'bienthe'  => new BientheResources($this->whenLoaded('bienTheSanPham')),
        ];
        if ($isAdmin) {
            $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
            $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
        }
        return $data;
    }
}
