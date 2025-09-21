<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bienthesp extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = true;

    protected $table = 'bienthe_sp';
    protected $fillable = [ 'gia', 'soluong', 'trangthai', 'uutien', 'id_sanpham','id_tenloai',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $casts = [
        'gia'      => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function loaiBienThe()
    {
        return $this->belongsTo(Loaibienthe::class, 'id_tenloai');
    }
    public function sanPham()
    {
        // 'id_sanpham' là khóa ngoại trong bảng 'bienthe_sp'
        // trỏ đến 'id' trong bảng 'san_pham'
        return $this->belongsTo(Sanpham::class, 'id_sanpham');
    }

}
