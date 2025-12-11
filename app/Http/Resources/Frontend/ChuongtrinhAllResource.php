<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChuongtrinhAllResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "id_chuongtrinh" => $this->id,
            "tieude_chuongtrinh" => $this->tieude,
            "quatangsukien" => $this->quatangsukien->map(function ($q) {
                return [
                    "id" => $q->id,
                    "id_bienthe"=> $q->bienthe->id ?? null,


                ];
            }),
        ];
    }
}
