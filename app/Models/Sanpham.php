<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SanPham extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'san_pham';

    protected $fillable = [
        'ten',
        'mota',
        'xuatxu',
        'sanxuat',
        'mediaurl',
        'trangthai',
        'luotxem',
        'id_thuonghieu',

        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',

    ];


    public function thuonghieu()
    {
        return $this->belongsTo(\App\Models\ThuongHieu::class, 'id_thuonghieu');
    }
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
    public function loaibienthe()
    {
        return $this->belongsToMany(Loaibienthe::class, 'bienthe_sp', 'id_sanpham', 'id_tenloai'); // làm phần tabs để SEO,  để làm 1 sản phẩm có ở nhiều loại sản phẩm và 1 loai san phẩm có thể có nhiều loại sản phẩm
    }

}


