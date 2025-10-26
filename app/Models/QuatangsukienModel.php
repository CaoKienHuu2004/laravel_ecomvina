<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuatangsukienModel extends Model
{
    use HasFactory,SoftDeletes;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'quatang_sukien';

    // Khóa chính
    protected $primaryKey = 'id';

    // Tự động quản lý created_at, updated_at
    public $timestamps = true;

    // Các cột có thể gán hàng loạt
    protected $fillable = [
        'id_bienthe',
        'id_thuonghieu',
        'id_sukien',
        'soluongapdung',
        'tieude',
        'thongtin',
        'trangthai',
    ];

    // Ép kiểu dữ liệu
    protected $casts = [
        'id_bienthe' => 'integer',
        'id_cuahang' => 'integer',
        'id_sukien' => 'integer',
        'soluongapdung' => 'integer',
        'tieude' => 'string',
        'thongtin' => 'string',
    ];

    // Giá trị mặc định
    protected $attributes = [
        'trangthai' => 'Hiển thị',
    ];

    // Quan hệ: Quà tặng thuộc về Biến thể
    public function bienthe()
    {
        return $this->belongsTo(BientheModel::class, 'id_bienthe');
    }

    // Quan hệ: Quà tặng thuộc về Cửa hàng
    public function thuonghieu()
    {
        return $this->belongsTo(ThuongHieuModel::class, 'id_cuahang','id');
    }

    // Quan hệ: Quà tặng thuộc về Sự kiện
    public function sukien()
    {
        return $this->belongsTo(SukienModel::class, 'id_sukien');
    }

    // Scope: chỉ lấy quà tặng đang hiển thị
    public function scopeHienThi($query)
    {
        return $query->where('trangthai', 'Hiển thị');
    }

    // Scope: chỉ lấy quà tặng đang tạm ẩn
    public function scopeTamAn($query)
    {
        return $query->where('trangthai', 'Tạm ẩn');
    }
}
