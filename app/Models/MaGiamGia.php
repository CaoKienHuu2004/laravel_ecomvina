<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaGiamGia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ma_giamgia';

    protected $fillable = [
        'magiamgia',
        'mota',
        'giatri',
        'dieukien',
        'ngaybatdau',
        'ngayketthuc',
        'trangthai',

        'created_at','updated_at','deleted_at'
    ];

    protected $casts = [
        'giatri'      => 'decimal:2',
        'ngaybatdau'  => 'datetime',
        'ngayketthuc' => 'datetime',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // // Quan hệ với đơn hàng
    public function donHang()
    {
        return $this->hasMany(DonHang::class, 'id_magiamgia');
    }

    // Kiểm tra xem mã có còn hiệu lực
    public function isActive(): bool
    {
        $today = now()->toDateString();
        return $this->trangthai === 'hoat_dong' && $this->ngaybatdau <= $today && $this->ngayketthuc >= $today;
    }
}
