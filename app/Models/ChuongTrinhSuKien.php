<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChuongTrinhSuKien extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'chuongtrinhsukien';

    protected $fillable = [
        'ten',
        'media',
        'mota',
        'ngaybatdau',
        'ngayketthuc',
        'trangthai',

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'ngaybatdau' => 'datetime',
        'ngayketthuc'=> 'datetime',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
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

    // public function quatangkhuyenmai()
    // {
    //     return $this->belongsToMany(SukienKhuyenMai::class, 'sukien_khuyenmai','id_sukien','id_khuyenmai');
    // }
    public function quatangkhuyenmai()
    {
        return $this->hasMany(QuatangKhuyenMai::class,'id_chuongtrinhsukien');
    }
    // public function sukienkhuyenmai()
    // {
    //     return $this->hasMany(SukienKhuyenMai::class, 'id_sukien');
    // }

}
