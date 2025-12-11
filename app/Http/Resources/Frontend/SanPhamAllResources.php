<?php

namespace App\Http\Resources\Frontend;

use DragonCode\Support\Helpers\Boolean;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;



/**
 * @OA\Schema(
 *     schema="SanPhamAllResources",
 *     type="object",
 *     title="SanPhamAllResources",
 *     description="Dữ liệu chi tiết sản phẩm cho trang chủ hoặc danh sách sản phẩm",
 *     @OA\Property(property="id", type="integer", example=1, description="ID sản phẩm"),
 *     @OA\Property(property="ten", type="string", example="Bánh quy ABC", description="Tên sản phẩm"),
 *     @OA\Property(property="slug", type="string", example="banh-quy-abc", description="Slug của sản phẩm"),
 *     @OA\Property(property="have_gift", type="boolean", example="true", description="trong thời gian có siêu quà tặng sự kiện, nếu nó có quà tặng sự kiện thì trả về true, không có thì false"),
 *     @OA\Property(property="hinh_anh", type="string", example="banh-quy.jpg", description="Ảnh đại diện sản phẩm, lấy từ hinhanhsanpham và lấy theo hình ảnh đầu tiên của sản phẩm và thời gian creaed_at mới nhất"),
 *     @OA\Property(property="luotxem", type="integer", example="1", description="vì detail có tự động +1 khi gọi tới show nên thêm vào"),
 *
 *     @OA\Property(
 *         property="rating",
 *         type="object",
 *         @OA\Property(property="average", type="number", format="float", example=4.8, description="Điểm đánh giá trung bình"),
 *         @OA\Property(property="count", type="integer", example=17000, description="Tổng số lượng đánh giá")
 *     ),
 *
 *     @OA\Property(
 *         property="sold",
 *         type="object",
 *         @OA\Property(property="total_sold", type="integer", example=500, description="Tổng số lượng đã bán, là dùng querry đế bảng chi tiết dơn hàng tính ra, chứ chưa lấy ở field luotban của tb:sanpham"),
 *         @OA\Property(property="total_quantity", type="integer", example=1000, description="Tổng tồn kho, Tổng số lượng của field soluong ở tb:bienthe")
 *     ),
 *
 *     @OA\Property(
 *         property="gia",
 *         type="object",
 *         @OA\Property(property="current", type="number", format="float", example=45000, description="Giá hiện tại sau khi giảm"),
 *         @OA\Property(property="before_discount", type="number", format="float", example=60000, description="Giá gốc trước khi giảm"),
 *         @OA\Property(property="discount_percent", type="integer", example=25, description="Phần trăm giảm giá")
 *     ),
 *
 *     @OA\Property(
 *         property="trangthai",
 *         type="object",
 *         @OA\Property(property="active", type="boolean", example=true, description="Trạng thái sản phẩm (đang hoạt động)"),
 *         @OA\Property(property="in_stock", type="boolean", example=true, description="Còn hàng hay không")
 *     )
 * )
 */

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
        $firstVariant = $this->bienthe->sortByDesc('giagoc')->first(); // Lấy biến thể có giá gốc cao nhất
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
            'id_chuongtrinh' => optional(
                $this->bienthe
                    ->flatMap(fn($bt) => $bt->quatangsukien)
                    ->first()
                    ->chuongtrinh ?? null
            )->id,

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

        ];
    }
}
