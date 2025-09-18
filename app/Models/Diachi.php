<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiaChi extends Model
{
    use SoftDeletes;

    protected $table = 'diachi_nguoidung';

    protected $fillable = [
        'ten',
        'sodienthoai',
        'thanhpho',
        'xaphuong',
        'sonha',
        'diachi',
        'trangthai',
        'id_nguoidung',
    ];

    /**
     * Quan hệ với bảng NguoiDung
     */
    public function nguoidung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoidung');
    }
}
