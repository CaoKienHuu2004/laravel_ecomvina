<?php

namespace App\Http\Resources\Frontend;

use App\Traits\CleanAndLimitText;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;


/**
 * @OA\Schema(
 *     schema="QuatangAllResource",
 *     type="object",
 *     title="QuatangAllResource",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="id_bienthe", type="integer"),
 *     @OA\Property(property="id_chuongtrinh", type="integer"),
 *     @OA\Property(property="dieukien", type="string"),
 *     @OA\Property(property="tieude", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="thongtin", type="string"),
 *     @OA\Property(property="hinhanh", type="string"),
 *     @OA\Property(property="luotxem", type="integer"),
 *     @OA\Property(property="ngaybatdau", type="string", format="date"),
 *     @OA\Property(property="thoigian_conlai", type="integer", description="Số ngày còn lại"),
 *     @OA\Property(property="ngayketthuc", type="string", format="date"),
 *     @OA\Property(property="trangthai", type="string"),
 * )
 */
class QuatangAllResource extends JsonResource
{
    use CleanAndLimitText;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        $today = Carbon::today();
        $endDate = Carbon::parse($this->ngayketthuc);
        $thoigian_conlai = $endDate->gte($today) ? $today->diffInDays($endDate) : 0; // Tính số ngày còn lại, nếu ngày kết thúc đã qua thì trả về 0
        $slug = Str::slug($this->tieude);
        return [
            'id' => $this->id,
            'id_bienthe' => $this->id_bienthe,
            'id_chuongtrinh' => $this->id_chuongtrinh,
            'dieukien' => $this->dieukien,
            'tieude' => $this->tieude,
            'slug' => $slug,
            'thongtin' => $this->cleanAndLimitText($this->thongtin),
            'hinhanh' => $this->hinhanh,
            'luotxem' => $this->luotxem,
            'ngaybatdau' => $this->ngaybatdau,
            'thoigian_conlai' => $thoigian_conlai,
            'ngayketthuc' => $this->ngayketthuc,
            'trangthai' => $this->trangthai,
        ];
    }


}
