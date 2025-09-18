<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhienDangNhapResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Xác định xem người dùng gọi API có phải admin hay không
        $isAdmin = $request->user()?->vaitro === 'admin';

        $data = [
            'id'            => $this->id,
            'nguoi_dung_id' => $this->nguoi_dung_id,
            'dia_chi_ip'    => $this->dia_chi_ip,
            'trinh_duyet'   => $this->trinh_duyet,
            'du_lieu'       => $this->du_lieu,
            'hoat_dong_cuoi'=> $this->hoat_dong_cuoi,

            // Quan hệ
            'nguoidung'     => new NguoidungResources($this->whenLoaded('nguoiDung')),
        ];

        // Chỉ admin mới thấy created_at và updated_at
        if ($isAdmin) {
            $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
            $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
        }

        return $data;
    }
}
