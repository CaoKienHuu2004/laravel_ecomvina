<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DanhMucResource extends JsonResource
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
            'ten' => $this->ten,
            'slug' => $this->slug,
            'logo' => $this->logo,
            'parent' => $this->parent,
            'trangthai' => $this->trangthai,
            'so_luong_con' => isset($this->danhmuccon) ? $this->danhmuccon->count() : 0,
            'danhmuccon' => isset($this->danhmuccon) ? DanhMucResource::collection($this->danhmuccon) : [],
        ];
    }
}
