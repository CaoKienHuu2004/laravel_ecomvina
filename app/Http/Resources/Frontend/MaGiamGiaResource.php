<?php

namespace App\Http\Resources\Frontend;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



/**
 * @OA\Schema(
 *     schema="MaGiamGia",
 *     type="object",
 *     title="Mã giảm giá",
 *     description="Thông tin chi tiết của mã giảm giá",
 *
 *     @OA\Property(property="id", type="integer", example=13),
 *     @OA\Property(property="magiamgia", type="string", example="SIEUTHIVINA2025"),
 *     @OA\Property(property="dieukien", type="string", example="200000"),
 *     @OA\Property(property="mota", type="string", example="Mã Voucher thuộc Nền tảng bán hàng trực tuyến Siêu Thị VinaMã Voucher thuộc Nền tảng bán hàng trực tuyến Siêu Thị Vina, dành tặng cho các khách hàng không kể bất kỳ ai với ưu đãi lên đến 20%. Áp dụng 1 lần duy nhất."),
 *     @OA\Property(property="giatri", type="integer", example=20000),
 *
 *     @OA\Property(
 *         property="ngaybatdau",
 *         type="string",
 *         format="date-time",
 *         example="2025-09-01T00:00:00+07:00"
 *     ),
 *
 *     @OA\Property(
 *         property="ngayketthuc",
 *         type="string",
 *         format="date-time",
 *         example="2025-09-01T00:00:00+07:00"
 *     ),
 *
 *     @OA\Property(property="trangthai", type="string", example="Hoạt động"),
 * )
 */
class MaGiamGiaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id'          => $this->id,
            'magiamgia'   => $this->magiamgia,
            'dieukien'    => $this->dieukien,
            'mota'        => $this->mota,
            'giatri'      => $this->giatri,

            // ISO 8601
            'ngaybatdau'  => $this->ngaybatdau
                ? $this->ngaybatdau->toIso8601String()
                : null,

            'ngayketthuc' => $this->ngayketthuc
                ? $this->ngayketthuc->toIso8601String()
                : null,

            'trangthai'   => $this->trangthai,
        ];
    }
}
