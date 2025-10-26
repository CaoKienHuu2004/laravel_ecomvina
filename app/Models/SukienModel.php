<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SukienModel extends Model
{
    use HasFactory,SoftDeletes;

    // Tên bảng
    protected $table = 'sukien';

    // Khóa chính
    protected $primaryKey = 'id';

    // Tự động quản lý timestamp
    public $timestamps = true;

    // Cho phép gán hàng loạt
    protected $fillable = [
        'tieude',
        'slug',
        'hinhanh',
        'noidung',
        'ngaybatdau',
        'ngayketthuc',
        'trangthai',
    ];

    // Ép kiểu dữ liệu
    protected $casts = [
        'ngaybatdau' => 'date',
        'ngayketthuc' => 'date',
    ];

    // Giá trị mặc định
    protected $attributes = [
        'trangthai' => 'Hiển thị',
    ];


    // Quan hệ: Một sự kiện có nhiều quà tặng
    public function quatang()
    {
        return $this->hasMany(QuatangsukienModel::class, 'id_sukien');
    }

    // Scope: lấy sự kiện đang hiển thị
    public function scopeHienThi($query)
    {
        return $query->where('trangthai', 'Hiển thị');
    }

    // Scope: lấy sự kiện tạm ẩn
    public function scopeTamAn($query)
    {
        return $query->where('trangthai', 'Tạm ẩn');
    }

    // Scope: chỉ lấy sự kiện đang diễn ra (theo ngày)
    public function scopeDangDienRa($query)
    {
        $today = now()->toDateString();
        return $query->where('ngaybatdau', '<=', $today)
                     ->where('ngayketthuc', '>=', $today);
    }


}
