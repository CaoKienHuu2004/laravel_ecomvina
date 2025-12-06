<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThongbaoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        // $item->created_at ? $item->created_at->toIso8601String() : null,
        return [
            'id'         => $this->id,
            'tieude'     => $this->tieude,
            'noidung'    => $this->noidung,
            'lienket'    => $this->lienket,
            'loaithongbao' => $this->loaithongbao,
            'trangthai'  => $this->trangthai,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'thoigian'      => $this->created_at
                            ? $this->created_at->diffForHumans()
                            : null,
        ];
    }
}
