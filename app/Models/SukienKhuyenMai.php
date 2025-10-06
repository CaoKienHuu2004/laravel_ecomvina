<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SukienKhuyenMai extends Model
{
    use HasFactory;

    protected $table = 'sukien_khuyenmai';

    protected $fillable = [
        'id_khuyenmai',
        'id_sukien',
    ];

    // Quan hệ tới quà tặng khuyến mãi
    public function khuyenmai()
    {
        return $this->belongsTo(QuatangKhuyenMai::class, 'id_khuyenmai');
    }

    // Quan hệ tới sự kiện
    public function sukien()
    {
        return $this->belongsTo(ChuongTrinhSuKien::class, 'id_sukien');
    }
}
