<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaGiamGia extends Model
{
    use HasFactory;

    protected $table = 'ma_giamgia';

    protected $fillable = [
        'magiamgia',
        'mota',
        'giatri',
        'dieukien',
        'ngaybatdau',
        'ngayketthuc',
        'trangthai',

        'created_at','updated_at'
    ];

    protected $casts = [
        'giatri'      => 'decimal:2',
        'ngaybatdau'  => 'datetime',
        'ngayketthuc' => 'datetime',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',

    ];

    /**
     * trong controller có thể check validate
     * public function rules()
      *  {
      *      return [
       *         'ngaybatdau' => 'required|date',
      *          'ngayketthuc' => 'required|date|after_or_equal:ngaybatdau',
      *      ];
       * }
     */
    protected static function booted()
    {
        static::saving(function ($model) {
            if ($model->ngayketthuc < $model->ngaybatdau) {
                throw new \Exception('Ngày kết thúc không thể nhỏ hơn ngày bắt đầu.');
            }
        });
    }

    // // Quan hệ với đơn hàng
    public function donHang()
    {
        return $this->hasMany(DonHang::class, 'id_magiamgia');
    }

    // Kiểm tra xem mã có còn hiệu lực
    public function isActive(): bool
    {
        $today = now()->toDateString();
        return $this->trangthai === 'hoat_dong' && $this->ngaybatdau <= $today && $this->ngayketthuc >= $today;
    }
}
