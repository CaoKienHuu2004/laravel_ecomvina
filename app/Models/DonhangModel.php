<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonhangModel extends Model
{
    use HasFactory, SoftDeletes;

    // Tên bảng trong database
    protected $table = 'donhang';

    // Khóa chính
    protected $primaryKey = 'id';

    // Các trường được phép gán hàng loạt
    protected $fillable = [
        'id_nguoidung',
        'id_phuongthuc',
        'id_magiamgia',
        'madon',
        'tongsoluong',
        'thanhtien',
        'trangthai',
    ];

    // Laravel tự xử lý created_at, updated_at, deleted_at
    public $timestamps = true;

    // Ép kiểu dữ liệu cho các cột
    protected $casts = [
        'id_nguoidung' => 'integer',
        'id_phuongthuc' => 'integer',
        'id_magiamgia' => 'integer',
        'tongsoluong' => 'integer',
        'thanhtien' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Giá trị mặc định
    protected $attributes = [
        'trangthai' => 'Chờ xử lý',
    ];

    // ==============================
    // QUAN HỆ GIỮA CÁC BẢNG
    // ==============================

    // Mỗi đơn hàng thuộc về 1 người dùng
    public function nguoidung()
    {
        return $this->belongsTo(NguoidungModel::class, 'id_nguoidung', 'id');
    }

    // Mỗi đơn hàng thuộc về 1 phương thức thanh toán
    public function phuongthuc()
    {
        return $this->belongsTo(PhuongthucModel::class, 'id_phuongthuc', 'id');
    }

    // Mỗi đơn hàng có thể có hoặc không có mã giảm giá
    public function magiamgia()
    {
        return $this->belongsTo(MagiamgiaModel::class, 'id_magiamgia', 'id');
    }

    // Một đơn hàng có nhiều chi tiết đơn hàng
    public function chitietdonhang()
    {
        return $this->hasMany(ChitietdonhangModel::class, 'id_donhang', 'id');
    }


}
