<?php

namespace App\Traits;

use App\Models\NguoidungModel;
use App\Models\ThongbaoModel;

trait SentMessToAdmin
{
    // gửi thông báo đến admin
    public function sentMessToAdmin($tieude, $noidung,$lienket)
    {
        $adminUser = NguoidungModel::where('vaitro', 'admin')->first();
        ThongbaoModel::create([
            'tieude' => $tieude,
            'noidung' => $noidung,
            'lienket' => $lienket ?? null,
            'id_nguoidung' => $adminUser->id,
            'trangthai' => "Chưa đọc",
        ]);
    }
}
