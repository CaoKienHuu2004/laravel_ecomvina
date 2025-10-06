<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
// use Carbon\Carbon;

class QuatangKhuyenMaiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()?->isAdmin() ?? false;

        // Ép về Carbon nếu không null
        // $ngaybatdau = $this->ngaybatdau ? Carbon::parse($this->ngaybatdau)->format('d-m-Y H:i:s') : null;
        // $ngayketthuc = $this->ngayketthuc ? Carbon::parse($this->ngayketthuc)->format('d-m-Y H:i:s') : null;

        $data = [
            'id'           => $this->id,
            'soluong'      => $this->soluong,
            'mota'         => $this->mota,
            'ngaybatdau'   => $this->ngaybatdau?->format('d-m-Y H:i:s'),
            'ngayketthuc'  => $this->ngayketthuc?->format('d-m-Y H:i:s'),
            'min_donhang'  => $this->min_donhang,
            'bienthe'      => new BientheResources($this->whenLoaded('bienthe')),
            'thuonghieu'   => new ThuonghieuResources($this->whenLoaded('thuonghieu')),
        ];
        if ($isAdmin) {
            $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
            $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
            $data['deleted_at'] = $this->deleted_at?->format('d-m-Y H:i:s');
        }
        return $data;
    }
}
