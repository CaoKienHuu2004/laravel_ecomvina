<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaGiamGiaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id'          => $this->id,
            'magiamgia'   => $this->magiamgia,
            'dieukien'    => $this->dieukien,
            'mota'        => $this->mota,
            'giatri'      => $this->giatri,

            // ISO 8601
            'ngaybatdau'  => $this->ngaybatdau
                ? $this->ngaybatdau->toIso8601String()
                : null,

            'ngayketthuc' => $this->ngayketthuc
                ? $this->ngayketthuc->toIso8601String()
                : null,

            'trangthai'   => $this->trangthai,
        ];
    }
}
