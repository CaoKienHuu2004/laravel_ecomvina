<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class HotSaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $mainImageUrl = optional($this->anhSanPham->first())->media;
        $priceBeforeDiscount = optional($this->bienThe->first())->gia; // Giá gạch ngang: 400.000 đ
        $currentPrice = $priceBeforeDiscount - optional($this->bienThe->first())->giagiam;          // Giá hiện tại: 300.000 đ

        $storeName = optional($this->thuonghieu)->ten ?? 'Không rõ cửa hàng';

        // Dữ liệu đánh giá: 'avg_rating' và tổng số lượng đánh giá (17k)
        $averageRating = round($this->avg_rating ?? 0, 1);
        $reviewCount = $this->danhgia->count(); // Tổng số lượng đánh giá

        return [
            // 1. Dữ liệu cơ bản
            'id' => $this->id,
            'ten' => $this->ten,
            'slug'          => str::slug($this->ten),

            // 2. Hình ảnh (Image)
            'mediaurl' => $this->mediaurl, // Ảnh đại diện chính

            // 3. Giá (Price)
            'gia' => [
                'current' => $currentPrice,
                'before_discount' => $priceBeforeDiscount,
                // Có thể tính % giảm giá nếu cần
                'discount_percent' => ($priceBeforeDiscount > $currentPrice && $priceBeforeDiscount > 0)
                                      ? round((($priceBeforeDiscount - $currentPrice) / $priceBeforeDiscount) * 100)
                                      : 0,
            ],

            // 4. Thông tin Cửa hàng/Thương hiệu (Store/Brand)
            'store' => [
                'name' => $storeName, // Siêu thị Vina
                'icon_url' => null, // Thêm đường dẫn icon nếu có (Ví dụ: )
            ],

            // 5. Đánh giá (Rating - dựa trên 'danhgia' và withAvg)
            'rating' => [
                'average' => $averageRating, // 4.8
                'count' => $reviewCount,     // (17k) -> Cần format số lớn ở frontend
                // 'formatted_count' => $this->formatReviewCount($reviewCount), // Có thể format ở đây hoặc frontend
            ],
        ];
    }
}
