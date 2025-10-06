<?php

namespace App\Models;

use App\Http\Resources\SukienKhuyenMaiResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChuongTrinhSuKien extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'chuongtrinhsukien';

    protected $fillable = [
        'ten',
        'media',
        'mota',
        'ngaybatdau',
        'ngayketthuc',
        'trangthai',

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'ngaybatdau' => 'datetime',
        'ngayketthuc'=> 'datetime',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];


    public function quatangkhuyenmai()
    {
        return $this->belongsToMany(SukienKhuyenMai::class, 'sukien_khuyenmai','id_sukien','id_khuyenmai');
    }
    // public function sukienkhuyenmai()
    // {
    //     return $this->hasMany(SukienKhuyenMai::class, 'id_sukien');
    // }

}
