<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donhang extends Model
{
    use HasFactory;

    protected $fillable = [
        'ma_don', 'ten_khach', 'sdt', 'tong_tien', 'trang_thai', 'thanh_toan'
    ];
}
