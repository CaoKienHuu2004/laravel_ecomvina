<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerQuangCao extends Model
{
    use HasFactory;

    // Tên bảng (nếu không khai báo thì Laravel sẽ suy ra là banner_quang_caos -> sai)
    protected $table = 'banner_quangcao';

    // Các cột có thể gán giá trị hàng loạt (mass assignment)
    protected $fillable = [
        'vitri',
        'hinhanh',
        'duongdan',
        'tieude',
        'trangthai',
    ];

    // Nếu muốn mặc định kiểu dữ liệu (casting)
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
