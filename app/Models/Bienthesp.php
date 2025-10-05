<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Bienthesp extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $table = 'bienthe_sp';
    protected $fillable = ['id_tenloai', 'gia', 'soluong', 'trangthai', 'uutien', 'id_sanpham'];

    public function loaiBienThe()
    {
        return $this->belongsTo(Loaibienthe::class, 'id_tenloai');
    }

    public function sanpham()
    {
        // 'id_sanpham' là khóa ngoại trong bảng 'bienthe_sp'
        // trỏ đến 'id' trong bảng 'san_pham'
        return $this->belongsTo(Sanpham::class, 'id_sanpham');
    }

    
}
