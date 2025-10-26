<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThuongHieuModel extends Model
{
    use HasFactory, SoftDeletes;

    // Nếu tên bảng không phải là "thuong_hieus" (mặc định Laravel sẽ tự thêm 's')
    protected $table = 'thuonghieu';

    // Khóa chính
    protected $primaryKey = 'id';

    // Cho phép gán dữ liệu hàng loạt
    protected $fillable = [
        'ten',
        'slug',
        'mota',
        'trangthai',
    ];

    // Dạng ENUM — có thể thêm helper
    const TRANGTHAI_HIENTHI = 'Hiển thị';
    const TRANGTHAI_TAMAN = 'Tạm ẩn';

    // Tự động cast kiểu dữ liệu (nếu cần)
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    public function sanpham(): HasMany
    {
        return $this->hasMany(SanphamModel::class, 'id_thuonghieu', 'id');
    }
    public function quatangsukien(): HasMany
    {
        return $this->hasMany(QuatangsukienModel::class, 'id_thuonghieu', 'id');
    }
}
