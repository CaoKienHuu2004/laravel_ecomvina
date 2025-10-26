<?php

namespace App\Models;

use App\Http\Controllers\API\DanhGiaAPI;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SanphamModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sanpham';
    protected $fillable = [
        'id_thuonghieu',
        'ten',
        'slug',
        'mota',
        'xuatxu',
        'sanxuat',
        'trangthai',
        'giamgia',
        'luotxem',
        'luotban',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function danhmuc(): BelongsToMany
    {
        return $this->belongsToMany(DanhmucModel::class, 'danhmuc_sanpham', 'id_sanpham', 'id_danhmuc');
    }

    public function bienthe(): HasMany
    {
        return $this->hasMany(BientheModel::class, 'id_sanpham');
    }

    public function thuonghieu(): BelongsTo
    {
        return $this->belongsTo(ThuongHieuModel::class, 'id_thuonghieu', 'id');
    }

    public function hinhanhsanpham(): HasMany
    {
        return $this->hasMany(HinhanhsanphamModel::class, 'id_sanpham');
    }

    public function chitietdonhang()
    {
        return $this->hasManyThrough(
            ChitietdonhangModel::class, // bảng cuối
            BientheModel::class,        // bảng trung gian
            'id_sanpham',          // khóa ngoại ở bảng BienThe trỏ tới SanPham
            'id_bienthe',          // khóa ngoại ở bảng ChiTietDonHang trỏ tới BienThe
            'id',                  // khóa chính ở SanPham
            'id'                   // khóa chính ở BienThe
        );
    }
    public function loaibienthe()
    {
        return $this->belongsToMany(LoaibientheModel::class, 'bienthe', 'id_sanpham', 'id_loaibienthe'); // làm phần tabs để SEO,  để làm 1 sản phẩm có ở nhiều loại sản phẩm và 1 loai san phẩm có thể có nhiều loại sản phẩm
    }
    public function danhgia()
    {
        return $this->hasMany(DanhgiaModel::class, 'id_sanpham', 'id');
    }
    public function yeuthich()
    {
        return $this->hasMany(YeuthichModel::class, 'id_sanpham', 'id');
    }


}
