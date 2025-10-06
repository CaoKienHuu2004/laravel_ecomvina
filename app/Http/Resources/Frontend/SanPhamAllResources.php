<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class SanPhamAllResources extends JsonResource
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

        // Dữ liệu đánh giá: 'avg_rating' và tổng số lượng đánh giá (17k)
        $averageRating = round($this->avg_rating ?? 0, 1);
        $reviewCount = $this->review_count; // Tổng số lượng đánh giá

        return [
            // 1. Dữ liệu cơ bản
            'id' => $this->id,
            'ten' => $this->ten,
            'slug'          => Str::slug($this->ten),


            'mediaurl' => $this->mediaurl, // Ảnh đại diện chính
            // . Đánh giá (Rating - dựa trên 'danhgia' và withAvg)
            'rating' => [
                'average' => $averageRating, // 4.8
                'count' => $reviewCount,     // (17k) -> Cần format số lớn ở frontend
                // 'formatted_count' => $this->formatReviewCount($reviewCount), // Có thể format ở đây hoặc frontend
            ],
            'sold' => [
                'total_sold' => $this->total_sold ?? 0, // Tổng số lượng đã bán
                'total_quantity' => $this->total_quantity ?? 0, // Tổng số lượng
                ],

            // . Giá (Price)
            'gia' => [
                'current' => $currentPrice,
                'before_discount' => $priceBeforeDiscount,
                // Có thể tính % giảm giá nếu cần
                'discount_percent' => ($priceBeforeDiscount > $currentPrice && $priceBeforeDiscount > 0)
                                      ? round((($priceBeforeDiscount - $currentPrice) / $priceBeforeDiscount) * 100)
                                      : 0,
            ],
            'trangthai' =>[
                'active' => $this->trangthai,
                'in_stock' => $this->total_quantity > 0,
                ]
        ];
    }
}
