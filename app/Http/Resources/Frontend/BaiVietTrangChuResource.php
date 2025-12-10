<?php

namespace App\Http\Resources\Frontend;

use App\Traits\CleanAndLimitText;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaiVietTrangChuResource extends JsonResource
{
    use CleanAndLimitText;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $plainContent = strip_tags($this->noidung);
        $shortContent = $this->cleanAndLimitTextPosts($plainContent,160);
        // $shortContent = mb_substr($plainContent, 0, 160, 'UTF-8');
        // if (mb_strlen($plainContent, 'UTF-8') > 160) {
        //     $shortContent .= '...';
        // }
        return [
            'id' => $this->id,
            'tieude' => $this->tieude,
            'slug' => $this->slug,
            'noidung' => $shortContent,
            'luotxem' => $this->luotxem ?? 0,
            'hinhanh' => $this->hinhanh,
            'trangthai' => $this->trangthai,
        ];
    }
}
