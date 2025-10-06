<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thuonghieu extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = true;

    protected $table = 'thuong_hieu';
    protected $fillable = ['ten','id_cuahang', 'mota','trangthai','media',//'namthanhlap',
        'created_at', 'updated_at','deleted_at'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
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
        return $this->hasMany(SanPham::class, 'id_thuonghieu','id');
    }

}
