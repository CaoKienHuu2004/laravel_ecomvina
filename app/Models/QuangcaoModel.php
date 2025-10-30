<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuangCaoModel extends Model
{
    use HasFactory;

    // Tên bảng trong CSDL
    protected $table = 'quangcao';

    // Khóa chính
    protected $primaryKey = 'id';

    // Migration không có timestamps
    public $timestamps = false;

    // Các cột được phép gán hàng loạt
    protected $fillable = [
        'vitri',
        'hinhanh',
        'lienket',
        'mota',
        'trangthai',
    ];

    /**
     * 🧠 Lấy danh sách quảng cáo đang hiển thị
     */
    public static function hienThi()
    {
        return self::where('trangthai', 'Hiển thị')->get();
    }

    /**
     * 🧠 Lấy quảng cáo theo vị trí (ví dụ: home_banner_slider)
     * @param string $vitri
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function theoViTri($vitri)
    {
        return self::where('vitri', $vitri)
            ->where('trangthai', 'Hiển thị')
            ->get();
    }

    /**
     * 🧠 Lấy quảng cáo ngẫu nhiên theo vị trí
     */
    public static function ngauNhienTheoViTri($vitri)
    {
        return self::where('vitri', $vitri)
            ->where('trangthai', 'Hiển thị')
            ->inRandomOrder()
            ->first();
    }
}
