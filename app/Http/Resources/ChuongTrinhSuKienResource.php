<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChuongTrinhSuKienResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()?->isAdmin() ?? false;
        $data = [
            'id'          => $this->id,
            'ten'         => $this->ten,
            'slug'        => $this->slug,
            'media'       => $this->media,
            'mota'        => $this->mota,
            'ngaybatdau'  => $this->ngaybatdau?->format('d-m-Y H:i:s'),
            'ngayketthuc' => $this->ngayketthuc?->format('d-m-Y H:i:s'),
            'trangthai'   => $this->trangthai,
            'quakhuyenmais'   => QuatangKhuyenMaiResource::collection($this->whenLoaded('quakhuyenmai')),
        ];
        if ($isAdmin) {
            $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
            $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
            $data['deleted_at'] = $this->deleted_at?->format('d-m-Y H:i:s');
        }
        return $data;
    }
}
