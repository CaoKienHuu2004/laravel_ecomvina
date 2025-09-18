<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SanphamResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Kiểm tra xem người dùng hiện tại có phải admin hay không
        $isAdmin = $request->user()?->isAdmin() ?? false;

        return [
            'id'           => $this->id,
            'ten_san_pham' => $this->ten,
            'mo_ta'        => $this->mota,
            'xuat_xu'      => $this->xuatxu,
            'san_xuat'     => $this->sanxuat,
            'url_video'    => $this->mediaurl,

            // Chỉ admin mới thấy lượt xem và ngày cập nhật
            'luot_xem'     => $this->when($isAdmin, $this->luotxem),
            'ngay_cap_nhat'=> $this->when($isAdmin, $this->updated_at->format('d-m-Y H:i:s')),

            // Tải các mối quan hệ có điều kiện
            'thuonghieus'  => new ThuonghieuResources($this->whenLoaded('thuonghieu')),
            'danhmucs'     => DanhmucResources::collection($this->whenLoaded('danhmuc')),
            'bienthes'     => BientheResources::collection($this->whenLoaded('bienThe')),
            'anhsanphams'  => AnhsanphamResources::collection($this->whenLoaded('anhSanPham')),
        ];
    }
}
