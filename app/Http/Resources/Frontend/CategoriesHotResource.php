<?php

namespace App\Http\Resources\Frontend;

use App\Http\Resources\Frontend\SanphamResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class CategoriesHotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ten' => $this->ten,
            'slug' => Str::slug($this->ten),
            'total_sold' => $this->total_sold,
            'sanphams' => SanphamResources::collection($this->sanphams)->resolve(), // trả về mảng thuần
        ];
    }
}
