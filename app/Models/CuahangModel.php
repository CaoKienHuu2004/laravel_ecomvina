<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuahangModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cuahang';
    protected $fillable = [
        'id_nguoidung',
        'ten',
        'slug',
        'mota',
        'logo',
        'bianen',
        'giayphep',
        'luottheodoi',
        'luotban',
        'sodienthoai',
        'diachi',
        'trangthai',
    ];
    protected $casts = [
        'luottheodoi' => 'integer',
        'luotban'     => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function nguoidung(): BelongsTo
    {
        return $this->belongsTo(NguoidungModel::class, 'id_nguoidung', 'id');
    }

    public function sanphams(): HasMany
    {
        return $this->hasMany(SanphamModel::class, 'id_cuahang');
    }
    public function sanpham(): HasMany
    {
        return $this->hasMany(SanphamModel::class, 'id_cuahang');
    }

}
