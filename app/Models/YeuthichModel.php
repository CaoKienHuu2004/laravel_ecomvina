<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YeuthichModel extends Model
{
    use HasFactory;

    // Tên bảng
    protected $table = 'yeuthich';

    // Khóa chính
    protected $primaryKey = 'id';

    // Các cột được phép gán hàng loạt
    protected $fillable = [
        'id_nguoidung',
        'id_sanpham',
        'trangthai',
    ];

    // Không có timestamps vì migration không tạo created_at / updated_at
    public $timestamps = false;

    /**
     * Quan hệ: Một mục yêu thích thuộc về một người dùng
     */
    public function nguoidung()
    {
        return $this->belongsTo(NguoidungModel::class, 'id_nguoidung', 'id');
    }

    /**
     * Quan hệ: Một mục yêu thích thuộc về một sản phẩm
     */
    public function sanpham()
    {
        return $this->belongsTo(SanphamModel::class, 'id_sanpham', 'id');
    }
    // Scope: chỉ lấy sản phẩm yêu thích đang hiển thị
    public function scopeHienThi($query)
    {
        return $query->where('trangthai', 'Hiển thị');
    }

    // Scope: lấy các sản phẩm yêu thích đang tạm ẩn
    public function scopeTamAn($query)
    {
        return $query->where('trangthai', 'Tạm ẩn');
    }
}
