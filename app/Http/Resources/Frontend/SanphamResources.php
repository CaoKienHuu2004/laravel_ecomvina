<?php
namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AnhsanphamResources;
use App\Http\Resources\BientheResources;
use App\Http\Resources\DanhGiaResource;
use App\Http\Resources\DanhmucResources;
use App\Http\Resources\LoaibientheResources;
use App\Http\Resources\ThuonghieuResources;
use Illuminate\Support\Str;

class SanphamResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()?->isAdmin() ?? false;

        $firstBienthe = $this->bienThe->first();
        $sellingPrice = $firstBienthe ? $firstBienthe->gia - $firstBienthe->giagiam : 0;
        $isSold = $firstBienthe ? $firstBienthe->soluong <= 0 : false;
        $isFree = $sellingPrice <= 0;

        // Xác định discount type
        if ($isSold) {
            $discountType = 'Sold';
        } elseif ($firstBienthe?->giagiam >= $firstBienthe?->gia) {
            $discountType = 'Miễn phí';
        } elseif ($firstBienthe?->giagiam > 0) {
            $discountType = 'Giảm tiền';
        } else {
            $discountType = null;
        }

        $data = [
            'id'            => $this->id,
            'ten'           => $this->ten,
            'slug'          => Str::slug($this->ten), // Tạo slug từ tên
            'mota'          => $this->mota,
            'xuatxu'        => $this->xuatxu,
            'sanxuat'       => $this->sanxuat,
            'mediaurl'      => $this->mediaurl,
            'image_url'     => $this->anhSanPham->first()?->media ?? null,
            'luotxem'       => $this->luotxem,
            'ngaycapnhat'   => $this->updated_at?->format('d-m-Y H:i:s'),

            'thuonghieu'    => new ThuonghieuResources($this->whenLoaded('thuonghieu')),
            'danhmucs'      => DanhmucResources::collection($this->whenLoaded('danhmuc')),
            'bienthes'      => BientheResources::collection($this->whenLoaded('bienThe')),
            'anhsanphams'   => AnhsanphamResources::collection($this->whenLoaded('anhSanPham')),
            'loaibienthes'  => LoaibientheResources::collection($this->whenLoaded('loaibienthe')),
            'danhgias'      => DanhGiaResource::collection($this->whenLoaded('danhgia')),

            // Các trường tính toán
            'original_price' => $firstBienthe?->gia,
            'discount_amount'=> $firstBienthe?->giagiam,
            'selling_price'  => $sellingPrice,
            'discount_type'  => $discountType,
            'is_free'        => $isFree,
            'is_sold'        => $isSold,
            'rating_average' => $this->danhgia->count() > 0 ? round($this->danhgia->avg('diem'), 2) : 0,
            'rating_count'   => $this->danhgia->count(),
            'seller_name'    => $this->thuonghieu?->ten,
            'product_meta'   => [
                'soluong_ton' => $firstBienthe?->soluong ?? 0,
                'mota_ngan'   => Str::limit($this->mota, 100),
            ],
        ];

        if ($isAdmin) {
            $data['created_at'] = $this->created_at ? $this->created_at->toIso8601String() : null;
            $data['updated_at'] = $this->updated_at ? $this->updated_at->toIso8601String() : null;
            $data['deleted_at'] = $this->deleted_at ? $this->deleted_at->toIso8601String() : null;
        }

        return $data;
    }
}





// use App\Http\Resources\AnhsanphamResources;
// use App\Http\Resources\BientheResources;
// use App\Http\Resources\DanhGiaResource;
// use App\Http\Resources\DanhmucResources;
// use App\Http\Resources\LoaibientheResources;
// use App\Http\Resources\ThuonghieuResources;
// use Illuminate\Http\Request;
// use Illuminate\Http\Resources\Json\JsonResource;

// class SanphamResources extends JsonResource
// {
//     /**
//      * Transform the resource into an array.
//      *
//      * @param Request $request
//      * @return array<string, mixed>
//      */
//     public function toArray(Request $request): array
//     {
//         $isAdmin = $request->user()?->isAdmin() ?? false;

//         // Lấy giá từ biến thể rẻ nhất
//         $minPrice = $this->bienThe->min('gia') ?? 0;
//         $maxPrice = $this->bienThe->max('gia') ?? 0;

//         // Tính giá gốc và giá giảm
//         $originalPrice = $maxPrice;
//         $salePrice = $minPrice;
//         $discountPercentage = $originalPrice > 0
//             ? round((($originalPrice - $salePrice) / $originalPrice) * 100) . '%'
//             : null;

//         // Lấy ảnh đầu tiên
//         $imageUrl = $this->anhSanPham->first()->duongdan ?? null;

//         $data = [
//             'id'   => $this->id,
//             'name' => $this->ten,

//             // Thông tin biến thể chính
//             'variant_info' => $this->bienThe->first()->ten ?? null,

//             // Ảnh
//             'image_url' => $imageUrl ? asset('storage/' . $imageUrl) : null,

//             // Giá
//             'discount_percentage' => $discountPercentage,
//             'original_price' => $originalPrice,
//             'original_price_formatted' => number_format($originalPrice, 0, ',', '.') . ' ₫',
//             'sale_price' => $salePrice,
//             'sale_price_formatted' => number_format($salePrice, 0, ',', '.') . ' ₫',

//             // Đánh giá
//             'rating' => [
//                 'average' => $this->avg_rating ? round($this->avg_rating, 1) : null,
//                 'count'   => $this->danhgia_count ?? 0,
//             ],

//             // Thương hiệu
//             'brand_or_tag' => $this->thuonghieu->ten ?? null,

//             // Danh mục
//             'categories' => $this->danhmuc->pluck('ten'),

//             // Link chi tiết
//             // 'detail_url' => route('api.sanphams.show', $this->id), // có vấn để routes laravel và nextjs sao đổi hướng được
//         ];

//         // Nếu là admin → thêm log thời gian
//         if ($isAdmin) {
//             $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
//             $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
//             $data['deleted_at'] = $this->deleted_at?->format('d-m-Y H:i:s');
//         }

//         return $data;
//     }

// }

//----------- bản chưa full-----------
// namespace App\Http\Resources\Frontend;

// use App\Http\Resources\AnhsanphamResources;
// use App\Http\Resources\BientheResources;
// use App\Http\Resources\DanhGiaResource;
// use App\Http\Resources\DanhmucResources;
// use App\Http\Resources\LoaibientheResources;
// use App\Http\Resources\ThuonghieuResources;
// use Illuminate\Http\Request;
// use Illuminate\Http\Resources\Json\JsonResource;

// class SanphamResources extends JsonResource
// {
//     /**
//      * Transform the resource into an array.
//      *
//      * @param Request $request
//      * @return array<string, mixed>
//      */
//     public function toArray(Request $request): array
//     {
//         // Kiểm tra xem người dùng hiện tại có phải admin hay không
//         $isAdmin = $request->user()?->isAdmin() ?? false;

//         $data = [
//             'id'           => $this->id,
//             'ten'           => $this->ten,
//             'mota'        => $this->mota,
//             'xuatxu'      => $this->xuatxu,
//             'sanxuat'     => $this->sanxuat,

//             'mediaurl'    => $this->mediaurl,
//             'luotxem'     => $this->luotxem,
//             'ngaycapnhat'=> $this->updated_at?->format('d-m-Y H:i:s'),
//             // Tải các mối quan hệ có điều kiện
//             'danhgias'  => DanhGiaResource::collection($this->whenLoaded('danhgia')),
//             'thuonghieu'  => new ThuonghieuResources($this->whenLoaded('thuonghieu')),
//             'danhmucs'     => DanhmucResources::collection($this->whenLoaded('danhmuc')),
//             'bienthes'     => BientheResources::collection($this->whenLoaded('bienThe')),
//             'anhsanphams'  => AnhsanphamResources::collection($this->whenLoaded('anhSanPham')),
//             'loaibienthes'  => LoaibientheResources::collection($this->whenLoaded('loaibienthe')),
//         ];
//         // Nếu admin, thêm thông tin thời gian
//         if ($isAdmin) {
//             $data['created_at'] = $this->created_at?->format('d-m-Y H:i:s');
//             $data['updated_at'] = $this->updated_at?->format('d-m-Y H:i:s');
//             $data['deleted_at'] = $this->deleted_at?->format('d-m-Y H:i:s');
//         }

//         return $data;
//     }
// }
//----------- bản chưa full-----------
// public function toArray($request)
//     {
//         // Giả định rằng $this là một instance của Model Product (Sản phẩm)
//         return [
//             // ID của sản phẩm (thường có)
//             // 'id' => $this->id,

//             // Tên sản phẩm
//             'name' => 'Instax Mini 12 Instant Film Camera - Green',

//             // SKU hoặc thông tin khác về màu sắc / biến thể
//             // Lưu ý: Tên hiển thị là Green, nhưng hình ảnh là màu Hồng (Pink)
//             'variant_info' => 'Green',

//             // Hình ảnh sản phẩm
//             'image_url' => asset('images/instax_mini_12_pink.png'), // Thay bằng URL hình ảnh thực tế

//             // Giảm giá tối đa
//             'discount_percentage' => '10%',

//             // Giá gốc (gạch ngang)
//             'original_price' => 450000,
//             'original_price_formatted' => '450.000 ₫',

//             // Giá bán hiện tại
//             'sale_price' => 300000,
//             'sale_price_formatted' => '300.000 ₫',

//             // Thông tin đánh giá
//             'rating' => [
//                 'average' => 4.8,
//                 'count' => '12K', // 12.000 lượt đánh giá
//             ],

//             // Nhãn hiệu/Nhà cung cấp (Giả sử Apple là nhãn hiệu hoặc nhà cung cấp ở đây)
//             'brand_or_tag' => 'Apple',

//             // Đường dẫn API chi tiết sản phẩm (nếu có)
//             // 'detail_url' => route('api.products.show', $this->id),
//         ];
//     }
