<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiohangModel extends Model
{
    use HasFactory;

    // Tên bảng trong database
    protected $table = 'giohang';

    // Khóa chính
    protected $primaryKey = 'id';

    // Các trường có thể gán hàng loạt
    protected $fillable = [
        'id_bienthe',
        'id_nguoidung',
        'soluong',
        'thanhtien',
        'trangthai',
    ];

    // Laravel tự động quản lý created_at và updated_at
    public $timestamps = true;

    // Ép kiểu dữ liệu
    protected $casts = [
        'id_bienthe' => 'integer',
        'id_nguoidung' => 'integer',
        'soluong' => 'integer',
        'thanhtien' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Giá trị mặc định cho các cột
    protected $attributes = [
        'trangthai' => 'Hiển thị',
    ];

    // ==============================
    // CÁC MỐI QUAN HỆ
    // ==============================

    // Mỗi sản phẩm trong giỏ thuộc về 1 người dùng
    public function nguoidung()
    {
        return $this->belongsTo(NguoidungModel::class, 'id_nguoidung', 'id');
    }

    // Mỗi sản phẩm trong giỏ hàng thuộc về 1 biến thể sản phẩm
    public function bienthe()
    {
        return $this->belongsTo(BientheModel::class, 'id_bienthe', 'id');
    }
}
