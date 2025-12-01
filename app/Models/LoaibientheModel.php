<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoaibientheModel extends Model
{
    use HasFactory;

    // Tên bảng trong database
    protected $table = 'loaibienthe';

    // Khóa chính của bảng
    protected $primaryKey = 'id';

    // Tắt timestamps vì trong migration không có created_at và updated_at
    public $timestamps = false;

    // Các cột được phép gán hàng loạt
    protected $fillable = [
        'ten',
        'trangthai',
    ];

    // Giá trị mặc định cho model
    protected $attributes = [
        'trangthai' => 'Hiển thị',
    ];

    public function bienthe()
    {
        return $this->hasMany(BientheModel::class, 'id_loaibienthe');
    }
    public function sanpham()
    {
        return $this->belongsToMany(SanphamModel::class, 'bienthe', 'id_loaibienthe', 'id_sanpham');
    }
}
