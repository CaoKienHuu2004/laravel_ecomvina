<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerQuangCaoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'vitri'     => $this->vitri,
            'hinhanh'   => $this->hinhanh,
            'duongdan'  => $this->duongdan,
            'tieude'    => $this->tieude,
            'trangthai' => $this->trangthai,
            'dayTao'    => $this->created_at?->format('Y-m-d H:i:s'),
            'dayCapNhat'=> $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
