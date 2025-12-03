<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Frontend\QuatangResource;
use App\Http\Resources\Frontend\SanphamCoQuatangCoBientheDeThemVaoGioResource;

class QuatangGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        QuatangResource::withoutWrapping();
        SanphamCoQuatangCoBientheDeThemVaoGioResource::withoutWrapping();
        return [
            'quatang' => new QuatangResource($this->quatang),
            'sanpham_coqua' => SanphamCoQuatangCoBientheDeThemVaoGioResource::collection($this->sanphamCoQua),
        ];
    }
}
