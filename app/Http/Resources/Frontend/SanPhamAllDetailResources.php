<?php

namespace App\Http\Resources\Frontend;

use App\Http\Resources\AnhsanphamResources;
use App\Http\Resources\DanhGiaResource;
use App\Http\Resources\LoaibientheResources;
use App\Models\Loaibienthe;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;



/**
 * @OA\Schema(
 *     schema="SanPhamAllDetailResources",
 *     title="Chi tiết sản phẩm",
 *     description="Thông tin chi tiết của sản phẩm bao gồm giá, biến thể, hình ảnh và đánh giá",
 *     @OA\Property(property="id", type="integer", example=12),
 *     @OA\Property(property="ten", type="string", example="Bánh quy ABC"),
 *     @OA\Property(property="slug", type="string", example="banh-quy-abc"),
 *     @OA\Property(
 *         property="rating",
 *         type="object",
 *         @OA\Property(property="average", type="number", format="float", example=4.8),
 *         @OA\Property(property="count", type="integer", example=134),
 *         @OA\Property(property="sao_5", type="integer", example=100),
 *         @OA\Property(property="sao_4", type="integer", example=20),
 *         @OA\Property(property="sao_3", type="integer", example=10),
 *         @OA\Property(property="sao_2", type="integer", example=3),
 *         @OA\Property(property="sao_1", type="integer", example=1),
 *     ),
 *     @OA\Property(
 *         property="sold",
 *         type="object",
 *         @OA\Property(property="total_sold", type="integer", example=350, description="Tổng số lượng đã bán, là dùng querry đế bảng chi tiết dơn hàng tính ra, chứ chưa lấy ở field luotban của tb:sanpham"),
 *         @OA\Property(property="total_quantity", type="integer", example=120, description="Tổng số lượng của field soluong ở tb:bienthe"),
 *     ),
 *     @OA\Property(
 *         property="gia",
 *         type="object",
 *         @OA\Property(property="current", type="number", format="float", example=180000),
 *         @OA\Property(property="before_discount", type="number", format="float", example=250000),
 *         @OA\Property(property="discount_percent", type="integer", example=28),
 *     ),
 *     @OA\Property(
 *         property="trangthai",
 *         type="object",
 *         @OA\Property(property="active", type="boolean", example=true),
 *         @OA\Property(property="in_stock", type="boolean", example=true),
 *     ),
 *     @OA\Property(
 *         property="loai_bien_the",
 *         type="array",
 *         @OA\Items(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="ten", type="string", example="Màu đỏ"),
 *             @OA\Property(property="trangthai", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Property(
 *         property="anh_san_pham",
 *         type="array",
 *         @OA\Items(
 *             @OA\Property(property="id", type="integer", example=3),
 *             @OA\Property(property="hinhanh", type="string", example="uploads/sp1.jpg"),
 *             @OA\Property(property="trangthai", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Property(
 *         property="danh_gia",
 *         type="array",
 *         @OA\Items(
 *             @OA\Property(property="id", type="integer", example=8),
 *             @OA\Property(property="diem", type="integer", example=5),
 *             @OA\Property(property="noidung", type="string", example="Sản phẩm rất tốt"),
 *             @OA\Property(property="hoten", type="string", example="Nguyễn Văn A")
 *         )
 *     ),
 *     @OA\Property(property="mota", type="string", example="Bánh quy hương bơ, giòn tan, vị thơm béo nhẹ.")
 * )
 */
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
        $firstVariant = $this->bienthe->sortByDesc('giagoc')->first(); // Lấy biến thể có giá gốc cao nhất
        $priceBeforeDiscount = optional($firstVariant)->giagoc ?? 0;   // Giá gốc

        // Tính giá sau giảm theo % (giamgia là phần trăm)
        $currentPrice = $priceBeforeDiscount * (1 - (($this->giamgia ?? 0) / 100));         // Giá hiện tại: 300.000 đ

        // Dữ liệu đánh giá: 'avg_rating' và tổng số lượng đánh giá (17k)
        $averageRating = round($this->avg_rating ?? 0, 1);
        $reviewCount = $this->review_count; // Tổng số lượng đánh giá

        return [
            // 1. Dữ liệu cơ bản
            'id' => $this->id,
            'ten' => $this->ten,
            'slug'          => $this->slug,


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
                // 'discount_percent' => ($priceBeforeDiscount > $currentPrice && $priceBeforeDiscount > 0)
                //                       ? round((($priceBeforeDiscount - $currentPrice) / $priceBeforeDiscount) * 100)
                //                       : 0,
                'discount_percent' => ($priceBeforeDiscount > $currentPrice && $priceBeforeDiscount > 0)
                      ? round((($priceBeforeDiscount - $currentPrice) / $priceBeforeDiscount) * 100)
                      : 0,
            ],
            'trangthai' =>[
                'active' => $this->trangthai,
                'in_stock' => $this->total_quantity > 0,
            ],
            // 'loai_bien_the' => LoaibientheResources::collection($this->whenLoaded('loaibienthe')),
            // 'loai_bien_the' => LoaibientheResources::collection($this->loaibienthe)->response()->getData(true),
            'loai_bien_the' => $this->loaibienthe
            ? $this->loaibienthe->map(function ($item) {
                return [
                    'id' => $item->id,
                    'ten' => $item->ten ?? null,
                    'trangthai' => $item->trangthai ?? null,
                ];
            })
            : [],
            // 'anh_san_pham' => AnhsanphamResources::collection($this->whenLoaded('hinhanhsanpham')),
            // 'anh_san_pham' => AnhsanphamResources::collection($this->anhSanPham)->response()->getData(true),
            'anh_san_pham' => $this->hinhanhsanpham
            ? $this->hinhanhsanpham->map(function ($anh) {
                return [
                    'id' => $anh->id,
                    'hinhanh' => $anh->hinhanh,
                    'trangthai' => $anh->trangthai,
                ];
            })
            : [],

            // 'danh_gia' => DanhGiaResource::collection($this->whenLoaded('danhgia')),
            // 'danh_gia' => DanhGiaResource::collection($this->danhgia)->response()->getData(true),
            'danh_gia' => $this->danhgia
            ? $this->danhgia->map(function ($dg) {
                return [
                    'id' => $dg->id,
                    'diem' => $dg->diem,
                    'noidung' => $dg->noidung,
                    'hoten' => $dg->nguoidung->hoten ?? null,
                ];
            })
            : [],
            'mota' => $this->mota

        ];
    }
}
