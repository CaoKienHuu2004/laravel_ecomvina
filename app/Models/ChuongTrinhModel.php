<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChuongTrinhModel extends Model
{
    use HasFactory;

    protected $table = 'chuongtrinh';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'tieude',
        'slug',
        'hinhanh',
        'noidung',
        'trangthai',
    ];

    public static function hienThi()
    {
        return self::where('trangthai', 'Hiển thị')->get();
    }

    public static function timTheoSlug($slug)
    {
        return self::where('slug', $slug)->first();
    }

    // Quan hệ 1 - N với bảng quatang_sukien
    public function quatangsukien()
    {
        return $this->hasMany(QuatangsukienModel::class, 'id_chuongtrinh');
    }
}
