<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonHangResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()?->isAdmin() ?? false;

        $data = [
            'id'           => $this->id,
            'ma_donhang'   => $this->ma_donhang,
            'tongtien'     => $this->tongtien,
            'tongsoluong'  => $this->tongsoluong,
            'ghichu'       => $this->ghichu,
            'ngaytao'      => $this->ngaytao?->format('d-m-Y H:i:s'),
            'trangthai'    => $this->trangthai,
            'nguoidung'    => new NguoidungResources($this->whenLoaded('nguoidung')),
            'magiamgia'    => new MaGiamGiaResource($this->whenLoaded('magiamgia')),
        ];
        if ($isAdmin) {
            $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
            $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
            $data['deleted_at'] = $this->deleted_at?->format('d-m-Y H:i:s');
        }
        return $data;
    }
}
