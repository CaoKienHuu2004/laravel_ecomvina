<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiaChi extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'diachi_nguoidung';

    protected $fillable = [
        'ten',
        'sodienthoai',
        // 'thanhpho',
        // 'xaphuong',
        // 'sonha',
        'diachi',
        'trangthai',
        'id_nguoidung',

        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Quan hệ với bảng NguoiDung
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoidung');
    }
}
