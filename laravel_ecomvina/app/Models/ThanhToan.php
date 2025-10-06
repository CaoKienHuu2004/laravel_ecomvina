<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThanhToan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'thanh_toan';

    protected $fillable = [
        'nganhang',
        'gia',
        'noidung',
        'magiaodich',
        'ngaythanhtoan',
        'trangthai',
        'id_donhang',

        'created_at','updated_at','deleted_at'
    ];

    protected $casts = [
        'gia'           => 'decimal:2',
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
}
