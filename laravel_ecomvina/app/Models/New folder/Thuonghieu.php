<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thuonghieu extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $table = 'thuong_hieu';
    protected $fillable = ['ten','id_cuahang', 'mota','trangthai','created_at', 'updated_at'];

    public function sanpham()
    {
        return $this->hasMany(Sanpham::class, 'id_thuonghieu', 'id');
    }

    public function cuaHang()
    {
        return $this->belongsTo(ThongTinNguoiBanHang::class, 'id_cuahang');
    }
    public function sanPhams()
    {
        return $this->hasMany(SanPham::class, 'id_thuonghieu');
    }

}
