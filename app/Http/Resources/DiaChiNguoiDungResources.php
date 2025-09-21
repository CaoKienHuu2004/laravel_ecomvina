<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiaChiNguoiDungResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        // Kiểm tra vai trò user, giả sử admin sẽ thấy full thông tin
        $isAdmin = $request->user()?->vaitro === 'admin';

        $data = [
            'id'          => $this->id,
            'ten'         => $this->ten,
            'sodienthoai' => $this->sodienthoai,
            'thanhpho'    => $this->thanhpho,
            'xaphuong'    => $this->xaphuong,
            'sonha'       => $this->sonha,
            'diachi'      => $this->diachi,
            'trangthai'   => $this->trangthai,

            'nguoi_dung'=> new NguoidungResources($this->whenLoaded('nguoiDung')),
        ];

        // Nếu là admin, thêm created_at & updated_at
        if ($isAdmin) {
            $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
            $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
            $data['deleted_at'] = $this->deleted_at?->format('d-m-Y H:i:s');
        }
        return $data;
    }
}
