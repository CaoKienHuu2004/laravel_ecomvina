<?php

namespace App\Http\Resources\Frontend;

use App\Http\Resources\AnhsanphamResources;
use App\Http\Resources\DanhGiaResource;
use App\Http\Resources\LoaibientheResources;
use App\Models\Loaibienthe;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class SanPhamAllDetailResources extends JsonResource
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


            // 'mediaurl' => $this->mediaurl, // Ảnh đại diện chính
            // . Đánh giá (Rating - dựa trên 'danhgia' và withAvg)
            'rating' => [
                'average' => $averageRating, // 4.8
                'count' => $reviewCount,     // (17k) -> Cần format số lớn ở frontend
                'sao_5'   => $this->danhgia->where('diem', 5)->count(),
                'sao_4'   => $this->danhgia->where('diem', 4)->count(),
                'sao_3'   => $this->danhgia->where('diem', 3)->count(),
                'sao_2'   => $this->danhgia->where('diem', 2)->count(),
                'sao_1'   => $this->danhgia->where('diem', 1)->count(),
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
            ],
            'loai_bien_the' => LoaibientheResources::collection($this->whenLoaded('loaibienthe')),
            // 'loai_bien_the' => LoaibientheResources::collection($this->loaibienthe)->response()->getData(true),
            'anh_san_pham' => AnhsanphamResources::collection($this->whenLoaded('anhSanPham')),
            // 'anh_san_pham' => AnhsanphamResources::collection($this->anhSanPham)->response()->getData(true),
            'danh_gia' => DanhGiaResource::collection($this->whenLoaded('danhgia')),
            // 'danh_gia' => DanhGiaResource::collection($this->danhgia)->response()->getData(true),
            'mota' => $this->mota

        ];
    }
}
