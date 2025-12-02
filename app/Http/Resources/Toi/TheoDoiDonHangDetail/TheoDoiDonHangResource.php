<?php

namespace App\Http\Resources\Toi\TheoDoiDonHangDetail;


use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Toi\TheoDoiDonHangDetail\ChiTietDonHangResource;
use App\Http\Resources\Toi\TheoDoiDonHangDetail\PhuongThucResource;
use App\Http\Resources\Toi\TheoDoiDonHangDetail\PhiVanChuyenResource;
use App\Http\Resources\Toi\TheoDoiDonHangDetail\DiaChiGiaoHangResource;



class TheoDoiDonHangResource extends JsonResource
{
    public function toArray($request)
    {
        // $converToStringCreated_at =  $this->created_at->format('d/m/Y H:i');
        return [
            'id' => $this->id,
            'madon' => $this->madon,
            'tongsoluong' => $this->tongsoluong,
            'tamtinh' => $this->tamtinh,
            'thanhtien' => $this->thanhtien, // int
            'trangthaithanhtoan' => $this->trangthaithanhtoan,
            'trangthai' => $this->trangthai,
            // 'created_at' => $converToStringCreated_at,
            // Sử dụng ->toIso8601String() để format đúng ISO 8601
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'chitietdonhang' => ChiTietDonHangResource::collection($this->chitietdonhang),
            'phuongthuc' => new PhuongThucResource($this->phuongthuc),
            'phivanchuyen' => new PhiVanChuyenResource($this->phivanchuyen),
            'diachigiaohang' => new DiaChiGiaoHangResource($this->diachigiaohang)
        ];
    }
}
