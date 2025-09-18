<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChuongTrinhSuKien extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'chuongtrinhsukien';

    protected $fillable = [
        'ten',
        'slug',
        'media',
        'mota',
        'ngaybatdau',
        'ngayketthuc',
        'trangthai',
    ];

    protected $casts = [
        'ngaybatdau' => 'datetime',
        'ngayketthuc'=> 'datetime',
    ];
}
