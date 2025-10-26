<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class YeuthichModel extends Model
{
    use HasFactory;

    // Tên bảng
    protected $table = 'yeuthich';

    // Khóa chính
    protected $primaryKey = 'id';

    // Laravel sẽ tự động quản lý created_at và updated_at
    public $timestamps = true;

    // Các cột cho phép gán hàng loạt
    protected $fillable = [
        'id_nguoidung',
        'id_sanpham',
        'trangthai',
    ];

    // Giá trị mặc định
    protected $attributes = [
        'trangthai' => 'Hiển thị',
    ];

    // Ép kiểu dữ liệu
    protected $casts = [
        'id_nguoidung' => 'integer',
        'id_sanpham' => 'integer',
        'trangthai' => 'string',
    ];

    // Quan hệ: Một yêu thích thuộc về một người dùng
    public function nguoidung()
    {
        return $this->belongsTo(NguoidungModel::class, 'id_nguoidung');
    }

    // Quan hệ: Một yêu thích thuộc về một sản phẩm
    public function sanpham()
    {
        return $this->belongsTo(SanphamModel::class, 'id_sanpham');
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
