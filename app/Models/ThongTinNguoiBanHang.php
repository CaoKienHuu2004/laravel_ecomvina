<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThongTinNguoiBanHang extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'thongtin_nguoibanhang';
    public $timestamps = true;
    protected $fillable = ['id_nguoidung',
        'ten_cuahang', 'giayphep_kinhdoanh', 'theodoi', 'luotban', 'logo',
        'bianen', 'mota', 'diachi', 'sodienthoai','email','trangthai',
        'created_at', 'updated_at','deleted_at'
    ];

    public function sanpham()
    {
        return $this->hasMany(SanPham::class, 'id_cuahang');
    }
    // public function thuongHieu()
    // {
    //     return $this->hasMany(Thuonghieu::class, 'id_cuahang');
    // }

    public function nguoidung()
    {
        return $this->belongsTo(Nguoidung::class, 'id_nguoidung','id');
    }
    public function quaTangKhuyenMai()
    {
        return $this->hasMany(QuatangKhuyenMai::class, 'id_cuahang','id');
    }
    // public function sanPhams()
    // {
    //     return $this->hasManyThrough(
    //         SanPham::class,
    //         ThuongHieu::class,
    //         'id_cuahang',    // FK ở ThuongHieu
    //         'id_thuonghieu', // FK ở SanPham
    //         'id',            // local key ở CuaHang
    //         'id'             // local key ở ThuongHieu
    //     );
    // }


}
