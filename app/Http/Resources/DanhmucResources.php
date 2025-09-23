<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DanhmucResources extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Kiểm tra quyền admin
        $isAdmin = $request->user()?->isAdmin() ?? false;

        $data = [
            'id'    => $this->id,
            'ten'   => $this->ten,
            'trangthai' => $this->trangthai,
            'sanphams'   => SanphamResources::collection($this->whenLoaded('sanphams')),
            'sanphams_count' => $this->when(isset($this->sanphams_count), $this->sanphams_count),
        ];
        // Nếu admin, thêm thông tin thời gian
        if ($isAdmin) {
            $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
            $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
            $data['deleted_at'] = $this->deleted_at?->format('d-m-Y H:i:s');
        }

        return $data;
    }
}
