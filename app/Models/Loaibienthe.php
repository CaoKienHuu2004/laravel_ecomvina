<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loaibienthe extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'loai_bienthe';
    public $timestamps = true;

    protected $fillable = ['ten','trangthai',
        'created_at','updated_at','deleted_at'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    public function bienthesps()
    {
        return $this->hasMany(Bienthesp::class, 'id_tenloai');
    }
    public function sanphams()
    {
        return $this->belongsToMany(Sanpham::class, 'bienthe_sp', 'id_tenloai', 'id_sanpham');
    }
    public function bienthesp()
    {
        return $this->hasMany(Bienthesp::class, 'id_tenloai');
    }
    public function sanpham()
    {
        return $this->belongsToMany(Sanpham::class, 'bienthe_sp', 'id_tenloai', 'id_sanpham');
    }

}
