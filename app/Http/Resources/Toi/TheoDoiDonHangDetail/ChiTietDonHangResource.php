<?php

namespace App\Http\Resources\Toi\TheoDoiDonHangDetail;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Toi\TheoDoiDonHangDetail\BienTheResource;



class ChiTietDonHangResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'soluong' => $this->soluong,
            'dongia' => $this->dongia,
            'trangthai' => $this->trangthai,
            'tong_tien' => $this->soluong * $this->dongia,
            'bienthe' => new BienTheResource($this->bienthe),
        ];
    }
}
