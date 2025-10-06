<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonHang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'don_hang';

    protected $fillable = [
        'ma_donhang',
        'tongtien',
        'tongsoluong',
        'ghichu',
        'ngaytao',
        'trangthai',
        'id_nguoidung',
        'id_magiamgia',

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'tongtien'  => 'decimal:2',
        'ngaytao'   => 'datetime',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',

    ];

    // Quan hệ với người dùng
    public function nguoidung()
    {
        // return $this->hasMany(DonHang::class, 'id_nguoidung');
        return $this->belongsTo(Nguoidung::class, 'id_nguoidung');
    }

    // Quan hệ với mã giảm giá
    public function magiamgia()
    {
        return $this->belongsTo(MaGiamGia::class, 'id_magiamgia');
        // return $this->hasMany(DonHang::class, 'id_magiamgia');

    }
    public function thanhtoan() {
        return $this->hasOne(ThanhToan::class, 'id_donhang');
    }

}
