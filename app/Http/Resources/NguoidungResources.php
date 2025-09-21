<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NguoidungResources extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Kiểm tra quyền admin
        $isAdmin = $request->user()?->isAdmin() ?? false;

        $data = [
            'id'          => $this->id,
            'email'       => $this->email,
            'avatar'      => $this->avatar,
            'hoten'       => $this->hoten,
            'gioitinh'    => $this->gioitinh,
            'ngaysinh'    => optional($this->ngaysinh)->format('d-m-Y'),
            'sodienthoai' => $this->sodienthoai,
            'trangthai'   => $this->trangthai,
            'diachis'     => DiaChiNguoiDungResources::collection($this->whenLoaded('diachi')),
        ];

        // Nếu là admin, trả thêm thông tin quản trị
        if ($isAdmin) {
            $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
            $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
            $data['deleted_at'] = $this->deleted_at?->format('d-m-Y H:i:s');
            $data['vaitro'] = $this->vaitro;
            $data['phienDangNhaps'] = PhienDangNhapResource::collection($this->whenLoaded('phienDangNhap'));
        }

        return $data;
    }
}
