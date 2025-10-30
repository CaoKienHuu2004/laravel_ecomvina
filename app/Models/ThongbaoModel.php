<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThongbaoModel extends Model
{
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'thongbao';

    // Khóa chính
    protected $primaryKey = 'id';

    // Bỏ timestamps vì migration không có created_at và updated_at
    public $timestamps = false;

    // Các cột được phép gán giá trị hàng loạt
    protected $fillable = [
        'id_nguoidung',
        'tieude',
        'noidung',
        'lienket',
        'trangthai',
    ];

    /**
     * Quan hệ: Thông báo thuộc về 1 người dùng
     */
    public function nguoidung()
    {
        return $this->belongsTo(NguoidungModel::class, 'id_nguoidung', 'id');
    }

    /**
     * Hàm tiện ích: đánh dấu đã đọc
     */
    public function danhDauDaDoc()
    {
        $this->trangthai = 'Đã đọc';
        $this->save();
    }

    /**
     * Hàm tiện ích: lấy tất cả thông báo chưa đọc của 1 người dùng
     */
    public static function chuaDocTheoNguoiDung($idNguoiDung)
    {
        return self::where('id_nguoidung', $idNguoiDung)
            ->where('trangthai', 'Chưa đọc')
            ->get();
    }
    // Scope: lấy thông báo bị tạm ẩn
    public function scopeTamAn($query)
    {
        return $query->where('trangthai', 'Tạm ẩn');
    }
}
