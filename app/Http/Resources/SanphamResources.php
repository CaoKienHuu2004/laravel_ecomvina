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

        $data = [
            'id'           => $this->id,
            'ten_san_pham' => $this->ten,
            'mo_ta'        => $this->mota,
            'xuat_xu'      => $this->xuatxu,
            'san_xuat'     => $this->sanxuat,
            'url_video'    => $this->mediaurl,
            'luot_xem'     => $this->luotxem,
            'ngay_cap_nhat'=> $this->updated_at?->format('d-m-Y H:i:s'),
            // Tải các mối quan hệ có điều kiện
            'thuonghieu'  => new ThuonghieuResources($this->whenLoaded('thuonghieu')),
            'danhmucs'     => DanhmucResources::collection($this->whenLoaded('danhmuc')),
            'bienthes'     => BientheResources::collection($this->whenLoaded('bienThe')),
            'anhsanphams'  => AnhsanphamResources::collection($this->whenLoaded('anhSanPham')),
            'loaibienthes'  => LoaibientheResources::collection($this->whenLoaded('loaibienthe')), // làm phần tabs để SEO, để làm 1 sản phẩm có ở nhiều loại sản phẩm và 1 loai san phẩm có thể có nhiều loại sản phẩm
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
