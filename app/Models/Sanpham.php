<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sanpham extends Model
{
    protected $table = 'san_pham';
    protected $fillable = ['id',
        'ten', 'mota', 'xuatxu', 'sanxuat', 'baohanh', 
        'mediaurl', 'trangthai', 'luotxem', 'id_thuonghieu',
        'created_at', 'updated_at'
    ];

     public function bienThe()
    {
        return $this->hasMany(Bienthesp::class, 'id_sanpham'); 
    }

    public function anhSanPham()
    {
        return $this->hasMany(Anhsp::class, 'id_sanpham');
    }
    public function danhmuc()
    {
        return $this->belongsToMany(Danhmuc::class, 'sanpham_danhmuc', 'id_sanpham', 'id_danhmuc');
    }

    public function thuonghieu()
    {
        return $this->belongsTo(Thuonghieu::class, 'id_thuonghieu', 'id');
    }

    
    use HasFactory;
}
