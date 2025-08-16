<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anhsp extends Model
{
    protected $table = 'anh_sanpham';
    protected $fillable = ['media', 'trang_thai', 'id_sanpham', 'created_at', 'updated_at'];
    use HasFactory;
}
