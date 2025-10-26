<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class BrandsHotResource extends JsonResource
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
            'slug'          => $this->slug,
            'mota'       => $this->mota,
            'total_sold' => $this->total_sold,
        ];
    }
}
