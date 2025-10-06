<?php

namespace App\Http\Resources\Frontend\DanhMuc;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
class DanhmucSelectionHomeHomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'ten'        => $this->ten,
            'slug'          => str::slug($this->ten),
            'media'          => $this->media ?? null,
            'trangthai'  => [
                'active' =>  $this->trangthai,
            ],
            'dayTao'    => $this->created_at?->format('Y-m-d H:i:s'),
            'dayCapNhat'=> $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
