<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhgiaModel extends Model
{
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'danhgia';

    // Khóa chính
    protected $primaryKey = 'id';

    // Các cột được phép gán hàng loạt
    protected $fillable = [
        'id_nguoidung',
        'id_sanpham',
        'id_chitietdonhang',
        'diem',
        'noidung',
        'trangthai',
    ];

    // Không có timestamps vì migration không tạo created_at / updated_at
    public $timestamps = false;

    /**
     * Quan hệ: Một đánh giá thuộc về một người dùng
     */
    public function nguoidung()
    {
        return $this->belongsTo(NguoidungModel::class, 'id_nguoidung', 'id');
    }

    /**
     * Quan hệ: Một đánh giá thuộc về một sản phẩm
     */
    public function sanpham()
    {
        return $this->belongsTo(SanphamModel::class, 'id_sanpham', 'id');
    }

    /**
     * Quan hệ: Một đánh giá thuộc về một chi tiết đơn hàng
     */
    public function chitietDonHang()
    {
        return $this->belongsTo(ChitietdonhangModel::class, 'id_chitietdonhang', 'id');
    }
}
