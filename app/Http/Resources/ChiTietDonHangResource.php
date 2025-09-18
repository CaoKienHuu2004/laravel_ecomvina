<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChiTietDonHangResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()?->isAdmin() ?? false;

        return [
            'id'        => $this->id,
            'gia'       => $this->gia,
            'soluong'   => $this->soluong,
            'tongtien'  => $this->tongtien,

            // Admin có thể xem chi tiết đơn hàng và biến thể
            'donhang'   => $isAdmin ? new DonHangResource($this->whenLoaded('donhang')) : null,
            'bienthe'   => $this->whenLoaded('bienthe'), // có thể hiển thị thông tin biến thể cho cả user
        ];
    }
}
