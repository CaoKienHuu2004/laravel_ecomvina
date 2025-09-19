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
            'username'    => $this->username,
            'email'       => $this->email,
            'avatar'      => $this->avatar,
            'hoten'       => $this->hoten,
            'gioitinh'    => $this->gioitinh,
            'ngaysinh'    => optional($this->ngaysinh)->format('d-m-Y'),
            'sodienthoai' => $this->sodienthoai,
            'vaitro'      => $this->vaitro,
            'trangthai'   => $this->trangthai,
            'diachis'     => DiaChiNguoiDungResources::collection($this->whenLoaded('diachi')),
        ];

        // Nếu là admin, trả thêm thông tin quản trị
        if ($isAdmin) {
            $data['created_at'] = $this->created_at->toISOString();
            $data['updated_at'] = $this->updated_at->toISOString();
            $data['phien_dang_nhap'] = PhienDangNhapResource::collection($this->whenLoaded('phienDangNhap'));
        }

        return $data;
    }
}
