<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChitietdonhangModel extends Model
{
    use HasFactory;

    // Tên bảng trong database
    protected $table = 'chitiet_donhang';

    // Khóa chính
    protected $primaryKey = 'id';

    // Các cột được phép gán hàng loạt
    protected $fillable = [
        'id_bienthe',
        'id_donhang',
        'soluong',
        'dongia',
    ];

    // Laravel tự xử lý created_at và updated_at
    public $timestamps = true;

    // Ép kiểu dữ liệu
    protected $casts = [
        'id_bienthe' => 'integer',
        'id_donhang' => 'integer',
        'soluong' => 'integer',
        'dongia' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ: Chi tiết đơn hàng thuộc về 1 đơn hàng
    public function donhang()
    {
        return $this->belongsTo(DonhangModel::class, 'id_donhang', 'id');
    }

    // Quan hệ: Chi tiết đơn hàng thuộc về 1 biến thể sản phẩm
    public function bienthe()
    {
        return $this->belongsTo(BientheModel::class, 'id_bienthe', 'id');
    }
    public function danhgia()
    {
        return $this->hasMany(DanhgiaModel::class, 'id_chitietdonhang', 'id');
    }

    // public function sanpham()
    // {
    //     // Nếu Chitietdonhang có khóa id_bienthe trỏ tới BientheModel
    //     // Và BientheModel trỏ tới SanphamModel
    //     return $this->belongsTo(BientheModel::class, 'id_bienthe')
    //                 ->with('sanpham'); // hoặc dùng belongsToThrough nếu có package
    // }

    public function sanpham()
    {
        return $this->hasOneThrough(
            SanphamModel::class,  // model đích
            BientheModel::class,  // model trung gian
            'id',                 // khóa chính ở bảng bienthe
            'id',                 // khóa chính ở bảng sanpham
            'id_bienthe',         // khóa ngoại ở bảng chitiet_donhang
            'id_sanpham'          // khóa ngoại ở bảng bienthe
        );
    }

}
