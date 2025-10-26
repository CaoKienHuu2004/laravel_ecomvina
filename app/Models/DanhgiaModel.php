<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DanhgiaModel extends Model
{
    use HasFactory, SoftDeletes;

    // Tên bảng trong database
    protected $table = 'danhgia';

    // Khóa chính
    protected $primaryKey = 'id';

    // Các cột được phép gán hàng loạt
    protected $fillable = [
        'id_sanpham',
        'id_nguoidung',
        'id_chitietdonhang',
        'diem',
        'noidung',
        'trangthai',
    ];

    // Laravel sẽ tự động quản lý created_at, updated_at, deleted_at
    public $timestamps = true;

    // Ép kiểu dữ liệu
    protected $casts = [
        'id_sanpham' => 'integer',
        'id_nguoidung' => 'integer',
        'id_chitietdonhang' => 'integer',
        'diem' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Giá trị mặc định cho các cột
    protected $attributes = [
        'trangthai' => 'Hiển thị',
    ];

    // Quan hệ: Một đánh giá thuộc về một sản phẩm
    public function sanpham()
    {
        return $this->belongsTo(SanphamModel::class, 'id_sanpham');
    }

    // Quan hệ: Một đánh giá thuộc về một người dùng
    public function nguoidung()
    {
        return $this->belongsTo(NguoidungModel::class, 'id_nguoidung');
    }
    public function chitietdonhang()
    {
        return $this->belongsTo(ChitietdonhangModel::class, 'id_chitietdonhang');
    }
}
