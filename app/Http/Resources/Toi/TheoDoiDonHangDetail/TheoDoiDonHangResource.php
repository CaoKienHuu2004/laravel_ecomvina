<?php

namespace App\Http\Resources\Toi\TheoDoiDonHangDetail;


use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Toi\TheoDoiDonHangDetail\ChiTietDonHangResource;
use App\Http\Resources\Toi\TheoDoiDonHangDetail\PhuongThucResource;
use App\Http\Resources\Toi\TheoDoiDonHangDetail\PhiVanChuyenResource;
use App\Http\Resources\Toi\TheoDoiDonHangDetail\DiaChiGiaoHangResource;

/**
 * @OA\Schema(
 *     schema="TheoDoiDonHangDetailResource",
 *     type="object",
 *     title="Theo dõi đơn hàng",
 *     description="Thông tin chi tiết đơn hàng theo từng trạng thái",
 *
 *     @OA\Property(property="id", type="integer", example=109),
 *     @OA\Property(property="madon", type="string", example="VNA1212497"),
 *     @OA\Property(property="tongsoluong", type="integer", example=2),
 *     @OA\Property(property="tamtinh", type="integer", example=1783000),
 *     @OA\Property(property="thanhtien", type="integer", example=1783000),
 *
 *     @OA\Property(property="trangthaithanhtoan", type="string", example="Chưa thanh toán"),
 *     @OA\Property(property="trangthai", type="string", example="Chờ xử lý"),
 *
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-12-09T12:49:28+07:00"),
 *
 *     @OA\Property(
 *         property="chitietdonhang",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ChiTietDonHangResource")
 *     ),
 *     @OA\Property(
 *         property="phuongthuc",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/PhuongThucThanhToanResource")
 *     ),
 *     @OA\Property(
 *         property="phivanchuyen",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/PhiVanChuyenResource")
 *     ),
 *     @OA\Property(
 *         property="diachigiaohang",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/DiaChiGiaoHangResource")
 *     ),
 *
 *     @OA\Property(property="nguoinhan", type="string", example="lee huy"),
 *     @OA\Property(property="diachinhan", type="string", example="175/19 đường số 20 Gò Vấp"),
 *     @OA\Property(property="sodienthoai", type="string", example="0983914069"),
 *     @OA\Property(property="khuvucgiao", type="string", example="Thành phố Hồ Chí Minh"),
 *
 *     @OA\Property(property="hinhthucvanchuyen", type="string", example="Nội thành"),
 *     @OA\Property(property="phigiaohang", type="integer", example=25000),
 *     @OA\Property(property="hinhthucthanhtoan", type="string", example="Chuyển khoản trực tiếp."),
 *
 *     @OA\Property(property="mavoucher", type="string", nullable=true, example=null),
 *     @OA\Property(property="giagiam", type="integer", example=0)
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

            'nguoinhan' => $this->nguoinhan,
            'diachinhan' => $this->diachinhan,
            'sodienthoai' => $this->sodienthoai,
            'khuvucgiao' => $this->khuvucgiao,

            'hinhthucvanchuyen' => $this->hinhthucvanchuyen,
            'phigiaohang' => $this->phigiaohang,
            'hinhthucthanhtoan' => $this->hinhthucthanhtoan,
            'mavoucher' => $this->mavoucher,
            'giagiam' => $this->giagiam,



            'chitietdonhang' => ChiTietDonHangResource::collection($this->chitietdonhang),
            'phuongthuc' => new PhuongThucResource($this->phuongthuc),
            'phivanchuyen' => new PhiVanChuyenResource($this->phivanchuyen),
            'diachigiaohang' => new DiaChiGiaoHangResource($this->diachigiaohang)
        ];
    }
}
