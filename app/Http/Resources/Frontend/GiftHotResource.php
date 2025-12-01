<?php

namespace App\Http\Resources\Frontend;


use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

use App\Traits\CleanAndLimitText;

class GiftHotResource extends JsonResource
{
    use CleanAndLimitText;
    public function toArray($request)
    {
        // Tính thời gian còn lại
        $remainingDays = null;
        if ($this->ngayketthuc) {
            $diff = Carbon::parse($this->ngayketthuc)->diff(Carbon::now());
            $remainingDays = "Còn lại {$diff->days} ngày {$diff->h} giờ";
        }
        $covertThongtin = $this->cleanAndLimitText($this->thongtin);
        return [
            'id' => $this->id,
            'tieude' => $this->tieude,
            'slug'   => Str::slug($this->tieude),
            'dieukien' => $this->dieukien,
            'thongtin' => $covertThongtin,
            'hinhanh' => $this->hinhanh,
            'luotxem' => $this->luotxem,
            'ngaybatdau' => $this->ngaybatdau,
            'ngayketthuc' => $this->ngayketthuc,
            'thoigian_conlai' => $remainingDays,
            'chuongtrinh' => $this->whenLoaded('chuongtrinh', function () {
                return [
                    'id' => $this->chuongtrinh->id,
                    'tieude' => $this->chuongtrinh->tieude,
                    'hinhanh' => $this->chuongtrinh->hinhanh,
                ];
            }),
        ];
    }
}
