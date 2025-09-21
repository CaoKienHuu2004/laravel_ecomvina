<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChiTietDonHangResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()?->isAdmin() ?? false;

        $data = [
            'id'        => $this->id,
            'gia'       => $this->gia,
            'soluong'   => $this->soluong,
            'tongtien'  => $this->tongtien,

            'donhang'   => new DonHangResource($this->whenLoaded('donhang')),
            'bienthe'   => new BientheResources($this->whenLoaded('bienthe')), // có thể hiển thị thông tin biến thể cho cả user
        ];
        if ($isAdmin) {
            $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
            $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
            $data['deleted_at'] = $this->deleted_at?->format('d-m-Y H:i:s');
        }
        return $data;
    }
}
