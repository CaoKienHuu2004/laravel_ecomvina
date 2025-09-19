<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NguoiDungAuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
        return [
                'id' => $this->id,
                'hoten' => $this->name,
                'email' => $this->email,
                // Thêm các trường khác của người dùng mà bạn muốn trả về
            ];
    }
}
