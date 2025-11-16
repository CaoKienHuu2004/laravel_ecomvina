<?php

namespace App\Http\Resources\Toi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiachiGiaohangResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hoten' => $this->hoten,
            'sodienthoai' => $this->sodienthoai,
            'diachi' => $this->diachi,
            'tinhthanh' => $this->tinhthanh,
            'trangthai' => $this->trangthai,
        ];
    }
}
