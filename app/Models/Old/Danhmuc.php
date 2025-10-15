<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Danhmuc extends Model
{
    use HasFactory;
    protected $table = 'danh_muc';
    public $timestamps = true;

    protected $fillable = ['ten', 'trangthai','created_at', 'updated_at'];
    public function sanpham()
    {
        return $this->belongsToMany(Sanpham::class, 'sanpham_danhmuc', 'id_danhmuc', 'id_sanpham');
    }
}
