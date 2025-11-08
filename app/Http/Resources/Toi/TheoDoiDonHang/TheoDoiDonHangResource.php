<?php

namespace App\Http\Resources\Toi\TheoDoiDonHang;


use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Toi\TheoDoiDonHang\ChiTietDonHangResource;
class TheoDoiDonHangResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'madon' => $this->madon,
            'tongsoluong' => $this->tongsoluong,
            'tamtinh' => $this->tamtinh,
            'thanhtien' => $this->thanhtien,
            'trangthaithanhtoan' => $this->trangthaithanhtoan,
            'trangthai' => $this->trangthai,
            'chitietdonhang' => ChiTietDonHangResource::collection($this->chitietdonhang),
        ];
    }
}
