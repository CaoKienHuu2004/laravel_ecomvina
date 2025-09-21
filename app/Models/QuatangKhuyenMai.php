<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuatangKhuyenMai extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'quatang_khuyenmai';

    protected $fillable = [
        'soluong',
        'mota',
        'ngaybatdau',
        'ngayketthuc',
        'min_donhang',
        'id_bienthe',
        'id_thuonghieu',

        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',

    ];

    // Quan hệ với biến thể sản phẩm
    public function bienthe()
    {
        return $this->belongsTo(Bienthesp::class, 'id_bienthe');
    }

    // Quan hệ với thương hiệu
    public function thuonghieu()
    {
        return $this->belongsTo(Thuonghieu::class, 'id_thuonghieu');
    }
}
