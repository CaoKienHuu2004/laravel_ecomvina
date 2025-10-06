<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietGioHang extends Model
{
    use HasFactory;

    protected $table = 'chitiet_giohang';

    protected $fillable = [
        'gio_hang_id',
        'bienthe_sp_id',
        'soluong',
        'tongtien',

        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: 1 chi tiết giỏ hàng thuộc về 1 giỏ hàng
    public function gioHang()
    {
        return $this->belongsTo(GioHang::class, 'gio_hang_id');
    }

    // Quan hệ: 1 chi tiết giỏ hàng thuộc về 1 biến thể sản phẩm
    public function bienTheSanPham()
    {
        return $this->belongsTo(BienTheSp::class, 'bienthe_sp_id');
    }
}
