<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class QuatangKhuyenMaiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()?->isAdmin() ?? false;

        // Ép về Carbon nếu không null
        $ngaybatdau = $this->ngaybatdau ? Carbon::parse($this->ngaybatdau)->format('d-m-Y H:i:s') : null;
        $ngayketthuc = $this->ngayketthuc ? Carbon::parse($this->ngayketthuc)->format('d-m-Y H:i:s') : null;

        return [
            'id'           => $this->id,
            'soluong'      => $this->soluong,
            'mota'         => $this->mota,
            'ngaybatdau'   => $ngaybatdau,
            'ngayketthuc'  => $ngayketthuc,
            'min_donhang'  => $this->min_donhang,

            // Chỉ admin mới thấy thông tin quan hệ
            'bienthe'      => $isAdmin ? $this->whenLoaded('bienthe') : null,
            'thuonghieu'   => $isAdmin ? $this->whenLoaded('thuonghieu') : null,
        ];
    }
}
