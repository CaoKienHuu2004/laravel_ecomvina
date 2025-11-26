<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="SanphamCoQuatangCoBientheDeThemVaoGioResource",
 *     type="object",
 *     title="SanphamCoQuatangCoBientheDeThemVaoGioResource",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="ten", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="have_gift", type="boolean"),
 *     @OA\Property(property="hinh_anh", type="string"),
 *     @OA\Property(property="rating", type="object",
 *         @OA\Property(property="average", type="number", format="float"),
 *         @OA\Property(property="count", type="integer"),
 *     ),
 *     @OA\Property(property="luotxem", type="integer"),
 *     @OA\Property(property="sold", type="object",
 *         @OA\Property(property="total_sold", type="integer"),
 *         @OA\Property(property="total_quantity", type="integer"),
 *     ),
 *     @OA\Property(property="gia", type="object",
 *         @OA\Property(property="current", type="number", format="float"),
 *         @OA\Property(property="before_discount", type="number", format="float"),
 *         @OA\Property(property="discount_percent", type="integer"),
 *     ),
 *     @OA\Property(property="trangthai", type="object",
 *         @OA\Property(property="active", type="string"),
 *         @OA\Property(property="in_stock", type="boolean"),
 *     ),
 *     @OA\Property(property="id_bienthe_de_them_vao_gio", type="integer"),
 * )
 */
class SanphamCoQuatangCoBientheDeThemVaoGioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $mainImageUrl = optional($this->anhSanPham->first())->media;
        $firstVariant = $this->bienthe
            ->filter(function ($variant) {
                return $variant->quatangsukien()
                    ->where('trangthai', 'Hiển thị')
                    ->whereDate('ngaybatdau', '<=', now())
                    ->whereDate('ngayketthuc', '>=', now())
                    ->whereNull('deleted_at')
                    ->exists();
            })
            ->sortByDesc('giagoc')
            ->first();
        $priceBeforeDiscount = optional($firstVariant)->giagoc ?? 0;   // Giá gốc

        // Tính giá sau giảm theo % (giamgia là phần trăm)
        $currentPrice = $priceBeforeDiscount * (1 - (($this->giamgia ?? 0) / 100));

        // Dữ liệu đánh giá: 'avg_rating' và tổng số lượng đánh giá (17k)
        $averageRating = round($this->avg_rating ?? 0, 1);
        $reviewCount = $this->review_count; // Tổng số lượng đánh giá

        $hinhanhsanpham = $this->hinhanhsanpham->sortByDesc('id')->first()->hinhanh;

        return [
            // 1. Dữ liệu cơ bản
            'id' => $this->id,
            'ten' => $this->ten,
            // 'slug'          => Str::slug($this->ten),
            'slug'          => $this->slug,
            'have_gift' => (bool) $this->have_gift ?? false,

            'hinh_anh' =>  $hinhanhsanpham,
            // . Đánh giá (Rating - dựa trên 'danhgia' và withAvg)
            'rating' => [
                'average' => $averageRating, // 4.8
                'count' => $reviewCount,     // (17k) -> Cần format số lớn ở frontend
                // 'formatted_count' => $this->formatReviewCount($reviewCount), // Có thể format ở đây hoặc frontend
            ],
            'luotxem' => $this->luotxem,



            'sold' => [
                'total_sold' => $this->bienthe ? $this->bienthe->sum('luotban') : 0, // tổng luotban của các biến thể
                // bình thường trong query controller củng đã có trường total_sold rồi mà chỉ có cái default là có à, nên làm như trên
                'total_quantity' => $this->bienthe ? $this->bienthe->sum('soluong') : 0, // nếu có cột 'soluong' thì tổng luôn
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

            'id_bienthe_de_them_vao_gio' => $firstVariant->id,

        ];
    }
}
