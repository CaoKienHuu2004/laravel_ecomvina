<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HanhviNguoiDungResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()?->isAdmin() ?? false;

        $data = [
            'id'          => $this->id,
            'id_nguoidung'=> $this->id_nguoidung,
            'id_sanpham'  => $this->id_sanpham,
            'id_bienthe'  => $this->id_bienthe,
            'hanhdong'    => $this->hanhdong,
            'ghichu'      => $this->ghichu,

            // Load quan hệ
            'nguoidung'   => new NguoidungResources($this->whenLoaded('nguoidung')),
            'sanpham'     => new SanphamResources($this->whenLoaded('sanpham')),
            'bienthe'     => new BientheResources($this->whenLoaded('bienthe')),
        ];

        // Nếu là admin mới thêm created_at và updated_at
        if ($isAdmin) {
            $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
            $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
        }

        return $data;
    }
}
