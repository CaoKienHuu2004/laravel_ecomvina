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

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'diem'    => 'float',
        'ngaydang'=> 'datetime',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Quan hệ với sản phẩm
    public function sanPham()
    {
        return $this->belongsTo(Sanpham::class, 'id_sanpham');
    }

    // Quan hệ với người dùng
    public function nguoiDung()
    {
        return $this->belongsTo(Nguoidung::class, 'id_nguoidung');
    }
}
