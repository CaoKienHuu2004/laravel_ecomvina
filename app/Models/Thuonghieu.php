<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thuonghieu extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $table = 'thuong_hieu';
    protected $fillable = ['ten', 'mota','trangthai','created_at', 'updated_at'];

    public function sanpham()
    {
        return $this->hasMany(Sanpham::class, 'id_thuonghieu', 'id');
    }
    
}
