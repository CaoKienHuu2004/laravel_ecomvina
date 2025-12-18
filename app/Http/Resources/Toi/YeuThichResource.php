<?php

namespace App\Http\Resources\Toi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class YeuThichResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $sanpham = $this->sanpham;
        $giaList = $sanpham->bienthe->pluck('giagoc');
        $bienthe = $sanpham->bienthe->orderBy('giagoc');
        $min = $giaList->min();
        $max = $giaList->max();
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'ten' =>$this->sanpham->ten,
            'id_sanpham' =>$this->sanpham->id,
            // 'giagiam_min' => $bienthe->giamgia ? $bienthe->giamgia * $min : $min,
            // 'giagiam_max' => $bienthe->giamgia ? $bienthe->giamgia * $max : $max,
            'gia_min' => $bienthe->giamgia ? $bienthe->giamgia * $min : $min,
            // 'gia_max' => $bienthe->giamgia ? $bienthe->giamgia * $max : $max,
            'hinhanh' =>$sanpham->hinhanhsanpham->first()->hinhanh,
            //
            // id?: number | string;
            // name?: string;
            // selling_price?: number;
        ];
    }
}
