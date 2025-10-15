<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bienthesp;

class ChitietDonhang extends Model
{
    use HasFactory;

    protected $table = 'chitiet_donhang';

    protected $fillable = [
        'gia', 'soluong', 'tongtien', 'id_donhang', 'id_bienthe'
    ];

    public $timestamps = false;

    //  Quan hệ tới đơn hàng
    public function donhang()
    {
        return $this->belongsTo(Donhang::class, 'id_donhang');
    }

    // Quan hệ tới biến thể sản phẩm (nếu có bảng bien_the hoặc san_pham_bien_the)
    public function bienthe()
    {
        return $this->belongsTo(Bienthesp::class, 'id_bienthe');
    }

    // Quan hệ tới sản phẩm
    public function sanpham()
    {
        return $this->belongsTo(Sanpham::class, 'id_sanpham');  // Make sure 'id_sanpham' is the foreign key
    }

}
