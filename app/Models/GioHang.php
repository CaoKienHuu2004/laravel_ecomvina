<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GioHang extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'gio_hang';

    protected $fillable = [
        'tongtien',
        'id_nguoidung',
        'guest_id',

        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];


    // 1 giỏ hàng thuộc về 1 người dùng
    public function nguoidung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoidung');
    }


    // chitiet bienTheSanPham sanpham
}

