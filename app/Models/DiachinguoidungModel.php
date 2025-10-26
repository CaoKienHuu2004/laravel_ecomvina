<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class DiachinguoidungModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'diachi_giaohang';

    protected $fillable = [
        'id_nguoidung',
        'hoten',
        'sodienthoai',
        'diachi',
        'trangthai',
    ];

    protected $casts = [
        // 'trangthai'  => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    //============================================================
    // MỐI QUAN HỆ (RELATIONSHIPS)
    //============================================================
    public function nguoidung(): BelongsTo
    {
        return $this->belongsTo(NguoidungModel::class, 'id_nguoidung');
    }
}

