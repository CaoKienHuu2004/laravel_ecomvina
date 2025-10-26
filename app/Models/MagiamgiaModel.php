<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MagiamgiaModel extends Model
{
    use HasFactory,SoftDeletes;

    // Tên bảng trong database
    protected $table = 'magiamgia';

    // Khóa chính
    protected $primaryKey = 'id';

    // Cho phép Laravel tự động quản lý created_at và updated_at
    public $timestamps = true;

    // Các cột có thể gán giá trị hàng loạt (mass assignment)
    protected $fillable = [
        'magiamgia',
        'dieukien',
        'mota',
        'giatri',
        'ngaybatdau',
        'ngayketthuc',
        'trangthai',
    ];

    // Kiểu dữ liệu cho từng cột
    protected $casts = [
        'magiamgia' => 'integer',
        'giatri' => 'integer',
        'ngaybatdau' => 'date',
        'ngayketthuc' => 'date',
    ];

    // Giá trị mặc định
    protected $attributes = [
        'trangthai' => 'Hoạt động',
    ];

    public function donhang() : HasMany
    {
        return $this->hasMany(DonhangModel::class, 'id_magiamgia');
    }


    // Thêm các scope hoặc hàm tiện ích
    public function scopeHoatDong($query)
    {
        return $query->where('trangthai', 'Hoạt động');
    }

    public function scopeHetHan($query)
    {
        return $query->whereDate('ngayketthuc', '<', now());
    }
}
