<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DanhGia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'danh_gia';

    protected $fillable = [
        'diem',
        'noidung',
        'media',
        'ngaydang',
        'trangthai',
        'id_sanpham',
        'id_nguoidung',
    ];

    protected $casts = [
        'diem'    => 'float',
        'ngaydang'=> 'date',
    ];

    // Quan hệ với sản phẩm
    public function sanpham()
    {
        return $this->belongsTo(Sanpham::class, 'id_sanpham');
    }

    // Quan hệ với người dùng
    public function nguoidung()
    {
        return $this->belongsTo(Nguoidung::class, 'id_nguoidung');
    }
}
