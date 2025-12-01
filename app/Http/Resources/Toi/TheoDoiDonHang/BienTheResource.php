<?php

namespace App\Http\Resources\Toi\TheoDoiDonHang;

use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="BienTheResource",
 *     type="object",
 *     title="BienTheResource",
 *     description="Thông tin biến thể của sản phẩm trong chi tiết đơn hàng",
 *     @OA\Property(property="id", type="integer", example=12, description="ID biến thể sản phẩm"),
 *     @OA\Property(property="giagoc", type="number", format="float", example=250000, description="Giá gốc của biến thể sản phẩm"),
 *     @OA\Property(property="soluong_kho", type="integer", example=15, description="Số lượng còn trong kho của biến thể"),
 *
 *     @OA\Property(
 *         property="loaibienthe",
 *         type="object",
 *         nullable=true,
 *         description="Loại biến thể (ví dụ: màu sắc, dung tích, kích cỡ...)",
 *         @OA\Property(property="id", type="integer", example=3, description="ID loại biến thể"),
 *         @OA\Property(property="ten", type="string", example="Màu Đen", description="Tên loại biến thể")
 *     ),
 *
 *     @OA\Property(
 *         property="sanpham",
 *         type="object",
 *         nullable=true,
 *         description="Thông tin sản phẩm gốc của biến thể",
 *         @OA\Property(property="id", type="integer", example=7, description="ID sản phẩm"),
 *         @OA\Property(property="ten", type="string", example="Tai nghe Bluetooth Sony WF-1000XM5", description="Tên sản phẩm"),
 *         @OA\Property(property="hinhanh", type="string", example="https://example.com/images/tai-nghe.jpg", description="Ảnh đại diện của sản phẩm")
 *     )
 * )
 */
class BienTheResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'giagoc' => $this->giagoc,
            'soluong_kho' => $this->soluong,
            'loaibienthe' => $this->loaibienthe ? [
                'id' => $this->loaibienthe->id,
                'ten' => $this->loaibienthe->ten,
            ] : null,
            'sanpham' => $this->sanpham ? [
                'id' => $this->sanpham->id,
                'ten' => $this->sanpham->ten,
                'hinhanh' => $this->sanpham->hinhanhsanpham->first()->hinhanh ?? null,
            ] : null,
        ];
    }
}
