<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonhangModel extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = 'donhang';


    protected $primaryKey = 'id';


    public $timestamps = true;


    protected $fillable = [
        'id_phuongthuc',
        'id_magiamgia',
        'id_nguoidung',
        'id_phivanchuyen',
        'id_diachigiaohang',
        'madon',
        'tongsoluong',
        'tamtinh',
        'thanhtien',
        'trangthaithanhtoan',
        'trangthai',
    ];

    // Ép kiểu dữ liệu
    protected $casts = [
        'tongsoluong' => 'integer',
        'tamtinh' => 'integer',
        'thanhtien' => 'integer',
    ];

    /**
     * 🔗 Quan hệ: Một đơn hàng thuộc về một người dùng
     */
    public function nguoidung()
    {
        return $this->belongsTo(NguoidungModel::class, 'id_nguoidung');
    }

    /**
     * 🔗 Quan hệ: Một đơn hàng có thể có một mã giảm giá
     */
    public function magiamgia()
    {
        return $this->belongsTo(MagiamgiaModel::class, 'id_magiamgia');
    }

    /**
     * 🔗 Quan hệ: Một đơn hàng có một phương thức thanh toán/vận chuyển
     */
    public function phuongthuc()
    {
        return $this->belongsTo(PhuongthucModel::class, 'id_phuongthuc');
    }

    /**
     * 🔗 Quan hệ: Một đơn hàng có một phí vận chuyển
     */
    public function phivanchuyen()
    {
        return $this->belongsTo(PhiVanChuyenModel::class, 'id_phivanchuyen');
    }

    /**
     * 🔗 Quan hệ: Một đơn hàng có một địa chỉ giao hàng
     */
    public function diachigiaohang()
    {
        return $this->belongsTo(DiaChiGiaoHang::class, 'id_diachigiaohang');
    }

    /**
     * 🔗 Quan hệ: Một đơn hàng có nhiều chi tiết đơn hàng
     */
    public function chitietdonhang()
    {
        return $this->hasMany(ChitietdonhangModel::class, 'id_donhang');
    }

    /**
     * 🧭 Scope lọc theo trạng thái xử lý
     */
    public function scopeTrangThai($query, $status)
    {
        return $query->where('trangthai', $status);
    }

    /**
     * 🧭 Scope lọc theo trạng thái thanh toán
     */
    public function scopeThanhToan($query, $status)
    {
        return $query->where('trangthaithanhtoan', $status);
    }

    /**
     * 🧮 Hàm tính tổng tiền đã thanh toán (có thể dùng khi thống kê)
     */
    public static function tongTienDaThanhToan()
    {
        return self::where('trangthaithanhtoan', 'Đã thanh toán')->sum('thanhtien');
    }
}
