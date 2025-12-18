<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChitietdonhangModel extends Model
{
    use HasFactory, SoftDeletes;

    // Tên bảng
    protected $table = 'chitiet_donhang';

    // Khóa chính
    protected $primaryKey = 'id';

    // Không dùng timestamps (vì migration không có created_at, updated_at)
    public $timestamps = false;

    // Các cột được phép gán hàng loạt
    protected $fillable = [
        'id_bienthe',
        // 'tenloaibienthe', //new
        // 'tensanpham', //new
        'id_donhang',
        'soluong',
        'dongia',
        'deleted_at'
    ];
    protected $casts = [
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];
    protected $hidden = [
        'deleted_at',
    ];
    /**
     * Quan hệ với model BienThe (1 biến thể có thể thuộc nhiều chi tiết đơn hàng)
     */
    public function bienthe()
    {
        return $this->belongsTo(BientheModel::class, 'id_bienthe');
    }

    /**
     * Quan hệ với model DonHang (1 đơn hàng có thể có nhiều chi tiết)
     */
    public function donhang()
    {
        return $this->belongsTo(DonhangModel::class, 'id_donhang');
    }

    /**
     * Tính tổng tiền cho một dòng chi tiết
     */
    public function getTongTienAttribute()
    {
        return $this->soluong * $this->dongia;
    }
}
