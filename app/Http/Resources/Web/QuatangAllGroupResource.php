<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Frontend\QuatangAllResource;

class QuatangAllGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $this->resource ở đây là object chứa { items, filters, pagination }
        // Do bạn truyền vào khi tạo resource: new QuatangAllGroupResource(compact('items', 'filters', 'pagination'))
        QuatangAllResource::withoutWrapping();
        return [
            'items' => QuatangAllResource::collection($this->result['items']),
            'filters' => $this->resource['filters'],
            'pagination' => $this->resource['pagination'],
        ];
    }
}
