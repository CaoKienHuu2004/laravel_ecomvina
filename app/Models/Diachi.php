<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diachi extends Model
{
    use HasFactory;
    protected $table = "diachi_nguoidung"; // Tên bảng trong database
    protected $fillable = [
        'ten',  // Tên người nhận
        'sodienthoai', // Số điện thoại người nhận
        'thanhpho',
        'xaphuong',
        'sonha',
        'diachi',
        'trangthai',
        'created_at',
        'updated_at',
        'id_nguoidung',
    ];

    public function nguoidung()
    {
        return $this->belongsTo(Nguoidung::class,'id_nguoidung','id');
    }
    public function diachi(){

    }
}
