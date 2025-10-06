<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Anhsp extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'anh_sanpham';
    public $timestamps = true;

    protected $fillable = ['media', 'trang_thai', 'id_sanpham',
        'created_at', 'updated_at','deleted_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    public function sanpham()
    {
        return $this->belongsTo(SanPham::class, 'id_sanpham');
    }

}
