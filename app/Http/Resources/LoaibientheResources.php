<?php

namespace App\Http\Resources;

use App\Models\Bienthesp;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoaibientheResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $isAdmin = $request->user()?->isAdmin() ?? false;
        $data = [
            'ten' => $this->ten,
            'trangthai' => $this->trangthai,
            'bienthesps'    => BientheResources::collection($this->whenLoaded('bienthesps')),
            'sanphams'      => SanphamResources::collection($this->whenLoaded('sanphams')),
        ];
        if ($isAdmin) {
            $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
            $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
            $data['deleted_at'] = $this->deleted_at?->format('d-m-Y H:i:s');
        }
        return $data;
    }
}
