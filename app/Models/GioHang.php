<?php

namespace App\Models;

use App\Http\Controllers\BientheController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GioHang extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'gio_hang';

    protected $fillable = [
        'soluong',
        'tongtien',
        'id_bienthesp',
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


    // Quan hệ với người dùng
    public function nguoidung()
    {
        return $this->belongsTo(Nguoidung::class, 'id_nguoidung');
    }

    // Quan hệ với sản phẩm
    public function bienthesp()
    {
        return $this->belongsTo(Bienthesp::class, 'id_bienthesp');
    }
}
