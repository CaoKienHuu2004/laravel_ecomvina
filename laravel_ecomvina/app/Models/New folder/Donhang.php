<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donhang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'don_hang';

    protected $fillable = [
        'ma_donhang', 'tongtien', 'tongsoluong', 'ghichu', 'trangthai', 'id_nguoidung', 'id_magiamgia'
    ];

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    // Quan hệ tới chi tiết đơn hàng
    public function chitiet()
    {
        return $this->hasMany(ChitietDonhang::class, 'id_donhang');
    }

    // Quan hệ tới khách hàng
    public function khachhang()
    {
        return $this->belongsTo(Nguoidung::class, 'id_nguoidung');
    }

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

    // Trạng thái dạng chữ
    public function getTrangthaiTextAttribute()
    {
        return match($this->trangthai) {
            0 => 'Chờ thanh toán',
            1 => 'Đang giao',
            2 => 'Đã giao',
            3 => 'Đã hủy',
            default => 'Không xác định',
        };
    }
    public function index()
    {
        $donhangs = DonHang::whereNull('deleted_at')->get();

        return view('danh-sach-don-hang', compact('donhangs'));
    }
}

