<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YeuThich extends Model
{
    use HasFactory;

    protected $table = 'yeu_thich';

    protected $fillable = [
        'trangthai',
        'id_sanpham',
        'id_nguoidung',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Quan hệ với sản phẩm
     */
    public function sanpham()
    {
        return $this->belongsTo(Sanpham::class, 'id_sanpham');
    }

    /**
     * Quan hệ với người dùng
     */
    public function nguoidung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoidung');
    }
}
