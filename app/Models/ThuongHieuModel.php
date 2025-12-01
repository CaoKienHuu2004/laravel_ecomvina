<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThuongHieuModel extends Model
{
    use HasFactory;

    // Tên bảng trong CSDL
    protected $table = 'thuonghieu';

    // Khóa chính
    protected $primaryKey = 'id';

    // Các cột cho phép gán hàng loạt
    protected $fillable = [
        'ten',
        'slug',
        'logo',
        'mota',
        'trangthai',
    ];

    // Giá trị mặc định
    protected $attributes = [
        'logo' => 'logo_shop.jpg',
        'trangthai' => 'Hoạt động',
    ];



    // Không có timestamps (vì migration không có created_at / updated_at)
    public $timestamps = false;

    /**
     * Quan hệ: Một thương hiệu có nhiều sản phẩm
     */
    public function sanpham()
    {
        return $this->hasMany(SanphamModel::class, 'id_thuonghieu', 'id');
    }
}
