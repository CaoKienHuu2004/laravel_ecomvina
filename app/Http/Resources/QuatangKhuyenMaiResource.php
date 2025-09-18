<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuatangKhuyenMaiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()?->isAdmin() ?? false;

        return [
            'id'           => $this->id,
            'soluong'      => $this->soluong,
            'mota'         => $this->mota,
            'ngaybatdau'   => $this->ngaybatdau?->format('d-m-Y H:i:s'),
            'ngayketthuc'  => $this->ngayketthuc?->format('d-m-Y H:i:s'),
            'min_donhang'  => $this->min_donhang,

            // Chỉ admin mới thấy thông tin quan hệ
            'bienthe'      => $isAdmin ? $this->whenLoaded('bienthe') : null,
            'thuonghieu'   => $isAdmin ? $this->whenLoaded('thuonghieu') : null,
        ];
    }
}
