<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThanhToanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()?->isAdmin() ?? false;

        $data[] = [
            'id'           => $this->id,
            'nganhang'     => $this->nganhang,
            'gia'          => $this->gia,
            'noidung'      => $this->noidung,
            'magiaodich'   => $this->magiaodich,
            'ngaythanhtoan'=> $this->ngaythanhtoan?->format('d-m-Y H:i:s'),
            'trangthai'    => $this->trangthai,
            'donhang'  => new DonHangResource($this->whenLoaded('donhang'))
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
