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

    public function loaibienthe()
    {
        return $this->belongsToMany(Loaibienthe::class, 'bienthe_sp', 'id_sanpham', 'id_tenloai'); // làm phần tabs để SEO,  để làm 1 sản phẩm có ở nhiều loại sản phẩm và 1 loai san phẩm có thể có nhiều loại sản phẩm
    }
    public function danhgia()
    {
        return $this->hasMany(DanhGia::class, 'id_sanpham', 'id');
    }
    ///------------------- Quan Hệ thông qua các bảng trung gian -----------------///
    // public function chiTietDonHang() // nếu chitiet_donhang FK đến san_pham
    // {
    //     return $this->hasMany(ChiTietDonHang::class, 'sanpham_id', 'id');
    // }

    public function danhmuc()
    {
        return $this->belongsToMany(Danhmuc::class, 'sanpham_danhmuc', 'id_sanpham', 'id_danhmuc');
    }
    public function chiTietDonHang()
    {
        return $this->hasManyThrough(
            ChiTietDonHang::class, // bảng cuối
            Bienthesp::class,        // bảng trung gian
            'id_sanpham',          // khóa ngoại ở bảng BienThe trỏ tới SanPham
            'id_bienthe',          // khóa ngoại ở bảng ChiTietDonHang trỏ tới BienThe
            'id',                  // khóa chính ở SanPham
            'id'                   // khóa chính ở BienThe
        );
    }


}


