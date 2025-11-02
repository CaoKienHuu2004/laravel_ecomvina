<?php

namespace App\Http\Resources\Toi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="GioHangResource",
 *     type="object",
 *     title="GioHangResource",
 *     description="Resource giỏ hàng của người dùng",
 *     @OA\Property(property="id_giohang", type="integer", example=1, description="ID giỏ hàng"),
 *     @OA\Property(property="id_nguoidung", type="integer", example=2, description="ID người dùng"),
 *     @OA\Property(property="trangthai", type="string", example="Hiển thị", description="Trạng thái giỏ hàng"),
 *     @OA\Property(
 *         property="bienthe",
 *         type="object",
 *         @OA\Property(property="soluong", type="integer", example=3, description="Số lượng sản phẩm"),
 *         @OA\Property(property="giagoc", type="number", format="float", example=100000, description="Giá gốc biến thể"),
 *         @OA\Property(property="thanhtien", type="number", format="float", example=300000, description="Thành tiền đã lưu trong giỏ hàng"),
 *         @OA\Property(property="tamtinh", type="number", format="float", example=300000, description="Tính tạm thời = soluong * giagoc"),
 *         @OA\Property(
 *             property="detail",
 *             type="object",
 *             @OA\Property(property="thuonghieu", type="string", example="Nike", description="Tên thương hiệu"),
 *             @OA\Property(property="tensanpham", type="string", example="Giày Thể Thao", description="Tên sản phẩm"),
 *             @OA\Property(property="loaisanpham", type="string", example="Size 42", description="Tên loại biến thể"),
 *             @OA\Property(property="giamgia", type="string", example="10%", description="Giảm giá của sản phẩm"),
 *             @OA\Property(property="giagoc", type="number", format="float", example=100000, description="Giá gốc sản phẩm"),
 *             @OA\Property(property="giaban", type="number", format="float", example=90000, description="Giá bán sau giảm"),
 *             @OA\Property(property="hinhanh", type="string", example="abc123.jpg", description="Hình ảnh đại diện sản phẩm")
 *         )
 *     )
 * )
 */
class GioHangResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_giohang' => $this->id,
            'id_nguoidung' => $this->id_nguoidung,
            'trangthai' => $this->trangthai,
            'bienthe' => [
                'soluong' => $this->soluong,
                'giagoc' => $this->bienthe->giagoc,
                'thanhtien' => $this->thanhtien,
                'tamtinh' => $this->soluong * $this->bienthe->giagoc,
                'detail' => [
                    'thuonghieu' => optional($this->bienthe->sanpham->thuonghieu)->ten,
                    'tensanpham' => optional($this->bienthe->sanpham)->ten,
                    'loaisanpham' => optional($this->bienthe->loaibienthe)->ten,
                    'giamgia' => $this->bienthe->sanpham->giamgia ? $this->bienthe->sanpham->giamgia.'%' : '0%',
                    'giagoc' => $this->bienthe->giagoc,
                    'giaban' => $this->bienthe->giagoc * (1 - ($this->bienthe->sanpham->giamgia ?? 0) / 100),
                    'hinhanh' => optional($this->bienthe->sanpham->hinhanhsanpham->first())->hinhanh,
                ],
            ],
            'bienthe_quatang' => $this->thanhtien == 0 ? [
                'soluong' => $this->soluong,
                'giagoc' => $this->bienthe->giagoc,
                'thanhtien' => $this->thanhtien,
                'tamtinh' => 0,
                'detail' => [
                    'thuonghieu' => optional($this->bienthe->sanpham->thuonghieu)->ten,
                    'tensanpham' => optional($this->bienthe->sanpham)->ten,
                    'loaisanpham' => optional($this->bienthe->loaibienthe)->ten,
                    'giagoc' => $this->bienthe->giagoc,
                    'hinhanh' => optional($this->bienthe->sanpham->hinhanhsanpham->first())->hinhanh,
                ],
            ] : null,
        ];
    }
}
