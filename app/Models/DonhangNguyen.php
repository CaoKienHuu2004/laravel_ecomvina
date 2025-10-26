<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonhangNguyen extends Model
{
    use HasFactory, SoftDeletes;

    // Bảng trong database
    protected $table = 'don_hang';



    // Cho phép gán hàng loạt các trường cần thiết
    protected $fillable = [
        'ma_donhang',
        'tongtien',
        'tongsoluong',
        'ghichu',
        'trangthai',
        'id_nguoidung',
        'id_magiamgia',
        'ngaytao',
        'id_phuongthuc_thanhtoan',
    ];

    // Nếu DB có cột `deleted_at` thì giữ SoftDeletes
    protected $dates = ['deleted_at'];

    // Laravel không dùng created_at / updated_at (mày đang dùng cột tự đặt là ngaytao)
    public $timestamps = true;

    // =========================
    // ==== QUAN HỆ MODEL =====
    // =========================

    // Chi tiết đơn hàng
    public function chitiet()
    {
        return $this->hasMany(ChitietDonhang::class, 'id_donhang');
    }

    // Khách hàng
    public function khachhang()
    {
        return $this->belongsTo(Nguoidung::class, 'id_nguoidung');
    }

    // =========================
    // ==== HÀM TÍNH TOÁN =====
    // =========================

    // Tổng tiền đơn hàng
    public function getTotalPrice()
    {
        return $this->chitiet->sum('tongtien');
    }

    // Tổng số lượng sản phẩm trong đơn
    public function getTotalQuantity()
    {
        return $this->chitiet->sum('soluong');
    }

    // =========================
    // ==== THUỘC TÍNH THÊM ===
    // =========================

    // Trạng thái dạng chữ
    public function getTrangthaiTextAttribute()
    {
        return match ($this->trangthai) {
            'da_xac_nhan'  => 'Đã xác nhận',
            'cho_xac_nhan' => 'Chờ xác nhận',
            'da_giao'      => 'Đã giao',
            'dang_giao'    => 'Đang giao',
            'da_huy'       => 'Đã hủy',
            default        => 'Không rõ',
        };
    }

    // =========================
    // ==== INDEX TẠM ========
    // =========================
    // Nếu mày có controller riêng rồi thì có thể bỏ phần này
    public function index()
    {
        $donhangs = self::whereNull('deleted_at')->get();
        return view('danh-sach-don-hang', compact('donhangs'));
    }
}
