<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class TukhoaResource extends JsonResource
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
            'dulieu' => $this->dulieu,
            'soluot' => $this->soluot,
            'dayTao' => $this->created_at?->format('Y-m-d H:i:s'),
            'dayCapNhat' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
