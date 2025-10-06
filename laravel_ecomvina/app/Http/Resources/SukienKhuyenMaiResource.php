<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SukienKhuyenMaiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()?->isAdmin() ?? false;
        $data = [
            'id'         => $this->id,
            'khuyenmai'  => new QuatangKhuyenMaiResource($this->whenLoaded('khuyenmai')),
            'sukien'     => new ChuongTrinhSuKienResource($this->whenLoaded('sukien')),
        ];
        // Nếu admin, thêm thông tin thời gian
        if ($isAdmin) {
            $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
            $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
        }

        return $data;
    }
}
