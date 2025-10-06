<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DanhGiaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()?->isAdmin() ?? false;

        $data = [
            'id'        => $this->id,
            'diem'      => $this->diem,
            'noidung'   => $this->noidung,
            'media'     => $this->media,
            'ngaydang'  => $this->ngaydang?->format('d-m-Y H:i:s'),
            'trangthai' => $this->trangthai,
            // Chỉ admin mới thấy thông tin người dùng
            'sanpham'   => new SanphamResources($this->whenLoaded('sanpham')),
            'nguoidung' => new NguoidungResources($this->whenLoaded('nguoidung')),
        ];
        if ($isAdmin) {

            $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
            $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
            $data['deleted_at'] = $this->deleted_at?->format('d-m-Y H:i:s');
        }
        return $data;
    }
}
