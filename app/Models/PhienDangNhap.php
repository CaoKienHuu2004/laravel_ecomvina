<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhienDangNhap extends Model
{
    use HasFactory;

    protected $table = 'phien_dang_nhap';
    public $incrementing = false; // Vì khóa chính là string
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nguoi_dung_id',
        'dia_chi_ip',
        'trinh_duyet',
        'du_lieu',
        'hoat_dong_cuoi',
    ];

    // Quan hệ ngược về Nguoidung
    public function nguoiDung()
    {
        return $this->belongsTo(Nguoidung::class, 'nguoi_dung_id');
    }
}
