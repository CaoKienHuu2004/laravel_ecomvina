<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThanhToanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()?->isAdmin() ?? false;

        return [
            'id'           => $this->id,
            'nganhang'     => $this->nganhang,
            'gia'          => $this->gia,
            'noidung'      => $this->noidung,
            'magiaodich'   => $this->magiaodich,
            'ngaythanhtoan'=> $this->ngaythanhtoan?->format('d-m-Y H:i:s'),
            'trangthai'    => $this->trangthai,

            // Chỉ admin mới thấy thông tin đơn hàng liên quan
            'donhang'      => $isAdmin ? new DonHangResource($this->whenLoaded('donhang')) : null,
        ];
    }
}
