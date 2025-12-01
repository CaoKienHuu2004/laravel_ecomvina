<?php

namespace App\Http\Resources\Toi\TheoDoiDonHang;


use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Toi\TheoDoiDonHang\ChiTietDonHangResource;


/**
 * @OA\Schema(
 *     schema="TheoDoiDonHangResource",
 *     type="object",
 *     title="TheoDoiDonHangResource",
 *     description="Thông tin đơn hàng người dùng theo dõi",
 *     @OA\Property(property="id", type="integer", example=12, description="ID đơn hàng"),
 *     @OA\Property(property="madon", type="string", example="DH20251108001", description="Mã đơn hàng"),
 *     @OA\Property(property="tongsoluong", type="integer", example=3, description="Tổng số lượng sản phẩm trong đơn hàng"),
 *     @OA\Property(property="tamtinh", type="number", format="integer", example=450000, description="Tạm tính đơn hàng"),
 *     @OA\Property(property="thanhtien", type="number", format="integer", example=480000, description="Tổng tiền sau khi tính phí và giảm giá"),
 *     @OA\Property(property="trangthaithanhtoan", type="string", example="Đã thanh toán", description="Trạng thái thanh toán của đơn hàng"),
 *     @OA\Property(property="trangthai", type="string", example="Đang giao hàng", description="Trạng thái hiện tại của đơn hàng"),
 *     @OA\Property(property="created_at", type="string", example="2025-12-01T20:40:11+07:00", description="Đặt hàng vào ngày format đúng ISO 8601"),
 *     @OA\Property(
 *         property="chitietdonhang",
 *         type="array",
 *         description="Danh sách chi tiết đơn hàng",
 *         @OA\Items(ref="#/components/schemas/ChiTietDonHangResource")
 *     )
 * )
 */
class TheoDoiDonHangResource extends JsonResource
{
    public function toArray($request)
    {
        // $converToStringCreated_at =  $this->created_at->format('d/m/Y H:i');
        return [
            'id' => $this->id,
            'madon' => $this->madon,
            'tongsoluong' => $this->tongsoluong,
            'tamtinh' => $this->tamtinh,
            'thanhtien' => $this->thanhtien, // int
            'trangthaithanhtoan' => $this->trangthaithanhtoan,
            'trangthai' => $this->trangthai,
            // 'created_at' => $converToStringCreated_at,
            // Sử dụng ->toIso8601String() để format đúng ISO 8601
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'chitietdonhang' => ChiTietDonHangResource::collection($this->chitietdonhang),
        ];
    }
}
