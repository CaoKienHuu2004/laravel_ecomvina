<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhuongThucThanhToan extends Model
{
    use HasFactory;

    protected $table = 'phuongthuc_thanhtoan';

    protected $fillable = [
        'ten',
        'ma',
        'mota',
        'trangthai',

        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function donhang()
    {
        return $this->hasMany(DonHang::class, 'id_phuongthuc_thanhtoan');
    }
    public function lichsuthanhtoan()
    {
        return $this->hasMany(ThanhToan::class, 'id_phuongthuc_thanhtoan');
    }

}
