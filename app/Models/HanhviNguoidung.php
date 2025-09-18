<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HanhviNguoidung extends Model
{
    protected $table = 'hanhvi_nguoidung';

    protected $fillable = [
        'id_nguoidung',
        'id_sanpham',
        'id_bienthe',
        'hanhdong',
        'ghichu',
    ];

    // Quan hệ với user
    public function user()
    {
        return $this->belongsTo(Nguoidung::class, 'id_nguoidung');
    }

    // Quan hệ với sản phẩm
    public function sanpham()
    {
        return $this->belongsTo(Sanpham::class, 'id_sanpham');
    }

    // Quan hệ với biến thể
    public function bienthe()
    {
        return $this->belongsTo(BientheSp::class, 'id_bienthe');
    }
}
