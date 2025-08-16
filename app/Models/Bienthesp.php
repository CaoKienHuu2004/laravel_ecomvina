<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bienthesp extends Model
{
    use HasFactory;
    protected $table = 'bienthe_sp';
    protected $fillable = ['id_tenloai', 'gia', 'soluong', 'trangthai', 'uutien', 'id_sanpham'];

    public function loaiBienThe()
    {
        return $this->belongsTo(Loaibienthe::class, 'id_tenloai');
    }

    
}
