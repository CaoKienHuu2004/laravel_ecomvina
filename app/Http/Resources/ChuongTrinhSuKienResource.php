<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChuongTrinhSuKienResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()?->isAdmin() ?? false;

        return [
            'id'          => $this->id,
            'ten'         => $this->ten,
            'slug'        => $this->slug,
            'media'       => $this->media,
            'mota'        => $this->mota,
            'ngaybatdau'  => $this->ngaybatdau?->format('d-m-Y H:i:s'),
            'ngayketthuc' => $this->ngayketthuc?->format('d-m-Y H:i:s'),

            // Chỉ admin mới thấy trạng thái thực tế
            'trangthai'   => $isAdmin ? $this->trangthai : null,
        ];
    }
}
