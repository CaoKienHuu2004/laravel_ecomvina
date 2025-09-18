<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GioHang extends Model
{
    use SoftDeletes;

    protected $table = 'gio_hang';

    protected $fillable = [
        'soluong',
        'tongtien',
        'id_sanpham',
        'id_nguoidung',
    ];

    // Quan hệ với người dùng
    public function nguoidung()
    {
        return $this->belongsTo(Nguoidung::class, 'id_nguoidung');
    }

    // Quan hệ với sản phẩm
    public function sanpham()
    {
        return $this->belongsTo(Sanpham::class, 'id_sanpham');
    }
}
