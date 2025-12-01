<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HinhanhsanphamModel extends Model
{
    use HasFactory, SoftDeletes;

    // Tên bảng trong database
    protected $table = 'hinhanh_sanpham';

    // Khóa chính
    protected $primaryKey = 'id';

    // Các cột cho phép gán hàng loạt
    protected $fillable = [
        'id_sanpham',
        'hinhanh',
        'trangthai',
        'deleted_at'
    ];

    // Giá trị mặc định
    protected $attributes = [
        'trangthai' => 'Hiển thị',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    // Không cần timestamps vì migration không có created_at / updated_at
    public $timestamps = false;

    /**
     * Quan hệ: Mỗi hình ảnh thuộc về 1 sản phẩm
     */
    public function sanpham()
    {
        return $this->belongsTo(SanphamModel::class, 'id_sanpham', 'id');
    }
}
