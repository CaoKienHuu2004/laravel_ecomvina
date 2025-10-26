<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class HinhanhsanphamModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'hinhanh_sanpham';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_sanpham',
        'hinhanh',
        'trangthai',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function sanpham()
    {
        return $this->belongsTo(SanphamModel::class, 'id_sanpham');
    }
}
