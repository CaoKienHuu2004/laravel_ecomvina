<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



/**
 * @OA\Schema(
 *     schema="BaiVietResource",
 *     type="object",
 *     title="Bài viết",
 *     required={"id", "tieude", "slug", "noidung", "luotxem", "trangthai"},
 *     @OA\Property(property="id", type="integer", example=5),
 *     @OA\Property(property="tieude", type="string", example="Tiêu đề bài viết"),
 *     @OA\Property(property="slug", type="string", example="tieu-de-bai-viet"),
 *     @OA\Property(property="noidung", type="string", example="Nội dung chi tiết bài viết..."),
 *     @OA\Property(property="luotxem", type="integer", example=10),
 *     @OA\Property(property="hinhanh", type="string", example="http://148.230.100.215/assets/client/images/posts/abc.jpg"),
 *     @OA\Property(property="trangthai", type="string", example="Hiển thị")
 * )
 */
class BaiVietResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
