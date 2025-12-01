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
        // $firstVariant = $this->bienthe->sortByDesc('giagoc')->first(); // Lấy biến thể có giá gốc cao nhất
        // $priceBeforeDiscount = optional($firstVariant)->giagoc ?? 0;   // Giá gốc
        // $currentPrice = $priceBeforeDiscount - ($this->giamgia)?? 0; // Giá sau giảm
        $firstVariant = $this->bienthe->sortByDesc('giagoc')->first(); // Lấy biến thể có giá gốc cao nhất
        $priceBeforeDiscount = optional($firstVariant)->giagoc ?? 0;   // Giá gốc

        // Tính giá sau giảm theo % (giamgia là phần trăm)
        $currentPrice = $priceBeforeDiscount * (1 - (($this->giamgia ?? 0) / 100));

        $storeName = optional($this->thuonghieu)->ten ?? 'Không rõ cửa hàng';

        // Dữ liệu đánh giá: 'avg_rating' và tổng số lượng đánh giá (17k)
        $averageRating = round($this->avg_rating ?? 0, 1);
        $reviewCount = $this->review_count; // Tổng số lượng đánh giá

        $hinhanhsanpham = optional(
            $this->hinhanhsanpham->sortByDesc('id')->first()
        )->hinhanh;

        return [
            // 1. Dữ liệu cơ bản
            'id' => $this->id,
            'ten' => $this->ten,
            'slug'          => $this->slug,

            // 2. Hình ảnh (Image)

            'hinh_anh' =>  $hinhanhsanpham,
            'thuonghieu' => $storeName,

            // 3. Giá (Price)
            'gia' => [
                'current' => $currentPrice,
                'before_discount' => $priceBeforeDiscount,
                // Có thể tính % giảm giá nếu cần
                // 'discount_percent' => ($priceBeforeDiscount > $currentPrice && $priceBeforeDiscount > 0)
                //                       ? round((($priceBeforeDiscount - $currentPrice) / $priceBeforeDiscount) * 100)
                //                       : 0,
                'discount_percent' => ($priceBeforeDiscount > $currentPrice && $priceBeforeDiscount > 0)
                      ? round((($priceBeforeDiscount - $currentPrice) / $priceBeforeDiscount) * 100)
                      : 0,
            ],

            // 4. Thông tin Cửa hàng/Thương hiệu (Store/Brand)
            // 'store' => [
            //     'name' => $storeName, // Siêu thị Vina
            //     'icon_url' => null, // Thêm đường dẫn icon nếu có (Ví dụ: )
            // ],

            // 5. Đánh giá (Rating - dựa trên 'danhgia' và withAvg)
            'rating' => [
                'average' => $averageRating, // 4.8
                'count' => $reviewCount,     // (17k) -> Cần format số lớn ở frontend
                // 'formatted_count' => $this->formatReviewCount($reviewCount), // Có thể format ở đây hoặc frontend
            ],
            'sold_count' => $this->total_sold ?? 0, // Thêm trường tổng số đã bán nếu cần
        ];
    }
}
