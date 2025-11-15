<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhmucSanphamModel extends Model
{
    use HasFactory;

    protected $table = 'danhmuc_sanpham'; // Tên bảng

    protected $primaryKey = 'id'; // Khóa chính

    public $timestamps = false; // Thường bảng pivot không có created_at, updated_at

    // Nếu bạn muốn gán hàng loạt các trường này
    protected $fillable = [
        'id_danhmuc',
        'id_sanpham',
    ];

    /**
     * Quan hệ tới Danhmuc (Danh mục)
     */
    public function danhmuc()
    {
        return $this->belongsTo(DanhmucModel::class, 'id_danhmuc');
    }

    /**
     * Quan hệ tới Sanpham (Sản phẩm)
     */
    public function sanpham()
    {
        return $this->belongsTo(SanphamModel::class, 'id_sanpham');
    }
}
