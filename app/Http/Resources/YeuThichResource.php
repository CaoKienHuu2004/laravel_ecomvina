<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class YeuThichResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        // Kiểm tra quyền admin
        $isAdmin = $request->user()?->isAdmin() ?? false;
         // Kiểm tra user hiện tại có role là admin không
        // $isAdmin = auth()->check() && auth()->user()->role === 'admin';
        $data = [
            'id'    => $this->id,
            'trangthai'   => $this->trangthai,
            'id_sanpham'        => $this->id_sanpham,
            'id_nguoidung' => $this->id_nguoidung,
            'sanpham'      => new SanphamResources($this->whenLoaded('sanpham')),
            'nguoidung'    => new NguoidungResources($this->whenLoaded('nguoidung')),
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
