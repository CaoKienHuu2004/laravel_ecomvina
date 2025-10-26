<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
class BientheModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bienthe';
    protected $fillable = [
        'id_loaibienthe',
        'id_sanpham',
        'giagoc',
        'soluong',
        'trangthai',
    ];
    protected $casts = [
        'soluong' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function sanpham(): BelongsTo
    {
        return $this->belongsTo(SanphamModel::class, 'id_sanpham');
    }

    public function loaibienthe(): BelongsTo
    {
        return $this->belongsTo(LoaibientheModel::class, 'id_loaibienthe');
    }
}
