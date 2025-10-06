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
        // Ép resource thành array để xử lý cho cả stdClass và Eloquent
        $data = (array) $this->resource;

        return [
            'id'         => $data['id'] ?? null,
            'ten'        => $data['ten'] ?? null,
            'slug'          => str::slug($this->ten),
            'media'          => $data['media'] ?? null,
            // 'namthanhlap'          => $data['namthanhlap'] ?? null,
            'mota'       => $data['mota'] ?? null,
            'total_sold' => $data['total_sold'] ?? 0,
        ];
    }
}
