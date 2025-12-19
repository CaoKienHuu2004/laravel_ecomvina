<?php

namespace App\Http\Resources\Toi\TheoDoiDonHangDetail;

use Illuminate\Http\Resources\Json\JsonResource;


class BienTheResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'giagoc' => $this->giagoc,
            'soluong_kho' => $this->soluong,
            'loaibienthe' => $this->loaibienthe ? [
                'id' => $this->loaibienthe->id,
                'ten' => $this->loaibienthe->ten,
            ] : null,
            'sanpham' => $this->sanpham ? [
                'id' => $this->sanpham->id,
                'ten' => $this->sanpham->ten,
                'hinhanh' => $this->sanpham->hinhanhsanpham->first()->hinhanh ?? null,
            ] : null,
        ];
    }
}
