<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GioHangCollectionResource extends JsonResource
{
    public function toArray($request): array
    {
        // Lấy thông tin user nếu có
        $firstItem = $this->first();
        $nguoidung = $firstItem?->nguoidung;

        return [
            'nguoidung' => [
                'id'    => $firstItem?->id_nguoidung,
                'name'  => $nguoidung?->getDisplayName() ?? $nguoidung?->name,
                'email' => $nguoidung?->email,
            ],
            'giohangs' => $this->map(function ($item) {
                return [
                    'id'        => $item->id,
                    'soluong'   => $item->soluong,
                    'tongtien'  => $item->tongtien,
                    'bienthesp' => $item->bienthesp ? new BientheResources($item->bienthesp) : null,
                    'sanpham'   => $item->bienthesp && $item->bienthesp->sanpham
                                    ? new SanPhamResources($item->bienthesp->sanpham)
                                    : null,
                    'created_at'=> $item->created_at?->format('d-m-Y H:i:s'),
                    'updated_at'=> $item->updated_at?->format('d-m-Y H:i:s'),
                ];
            })->values(), // reset keys để trả về mảng chuẩn JSON
        ];
    }
}
