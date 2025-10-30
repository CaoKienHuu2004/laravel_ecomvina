<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BientheModel extends Model
{
    use HasFactory, SoftDeletes;

    // Tên bảng
    protected $table = 'bienthe';

    // Khóa chính
    protected $primaryKey = 'id';

    // Không có timestamps (vì migration không có created_at, updated_at)
    public $timestamps = false;

    // Các cột được phép gán hàng loạt
    protected $fillable = [
        'id_sanpham',
        'id_loaibienthe',
        'giagoc',
        'soluong',
        'luottang',
        'luotban',
        'trangthai',
        'deleted_at',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    /**
     * Quan hệ: Biến thể thuộc về 1 sản phẩm
     */
    public function sanpham()
    {
        return $this->belongsTo(SanphamModel::class, 'id_sanpham');
    }

    /**
     * Quan hệ: Biến thể thuộc về 1 loại biến thể (vd: Màu sắc, Kích thước, ...)
     */
    public function loaibienthe()
    {
        return $this->belongsTo(LoaibientheModel::class, 'id_loaibienthe');
    }

    /**
     * Quan hệ: Một biến thể có thể nằm trong nhiều chi tiết đơn hàng
     */
    public function chitietdonhang()
    {
        return $this->hasMany(ChitietdonhangModel::class, 'id_bienthe');
    }
    public function giohang()
    {
        return $this->hasMany(GiohangModel::class, 'id_bienthe');
    }
    public function quatangsukien()
    {
        return $this->hasMany(QuatangsukienModel::class, 'id_bienthe');
    }

    /**
     * Tính trạng thái kho (tự động)
     */
    public function getTinhTrangKhoAttribute()
    {
        if ($this->soluong <= 0) {
            return 'Hết hàng';
        } elseif ($this->soluong < 5) {
            return 'Sắp hết hàng';
        }
        return 'Còn hàng';
    }
}
