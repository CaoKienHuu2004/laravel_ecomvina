<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThanhToan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lichsu_thanhtoan';

    protected $fillable = [
        'nganhang',
        'gia',
        'noidung',
        'magiaodich',
        'ngaythanhtoan',
        'trangthai',
        'id_donhang',
        'id_phuongthuc_thanhtoan',

        'created_at','updated_at','deleted_at'
    ];

    protected $casts = [
        'ngaythanhtoan' => 'datetime',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Quan hệ với đơn hàng
    public function donhang()
    {
        return $this->belongsTo(DonHang::class, 'id_donhang');
    }
    public function phuongthucthanhtoan()
    {
        return $this->belongsTo(PhuongThucThanhToan::class, 'id_phuongthuc_thanhtoan');
    }
}
