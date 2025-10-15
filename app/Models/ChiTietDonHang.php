<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChiTietDonHang extends Model
{
    use HasFactory;

    protected $table = 'chitiet_donhang';

    protected $fillable = [
        'gia',
        'soluong',
        'tongtien',
        'id_donhang',
        'id_bienthe',

        'created_at',
        'updated_at',
    ];

    protected $casts = [

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ với đơn hàng
    public function donhang()
    {
        return $this->belongsTo(DonHang::class, 'id_donhang');
    }

    // Quan hệ với biến thể sản phẩm
    public function bienthe()
    {
        return $this->belongsTo(Bienthesp::class, 'id_bienthe');
    }
}
