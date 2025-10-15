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
        'mota',
        'soluong',
        'ngaybatdau',
        'ngayketthuc',

        'soluongapdung',
        'kieuapdung',

        'id_bienthe',
        'id_cuahang',
        'id_chuongtrinhsukien',

        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $casts = [
        'ngaybatdau' => 'datetime',
        'ngayketthuc' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',

    ];

    // Quan hệ với biến thể sản phẩm
    public function bienthe()
    {
        return $this->belongsTo(Bienthesp::class, 'id_bienthe');
    }

    // Quan hệ với chuongtrinhsukien
    public function chuongtrinhsukien()
    {
        return $this->belongsTo(ChuongTrinhSuKien::class, 'id_chuongtrinhsukien');
    }
    // Quan hệ với chuongtrinhsukien
    public function cuaHang()
    {
        return $this->belongsTo(ThongTinNguoiBanHang::class, 'id_cuahang');
    }
    /**
     * trong controller có thể check
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

}
