<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class SanphamModel extends Model
{
    use HasFactory, SoftDeletes;

    // Tên bảng
    protected $table = 'sanpham';

    // Khóa chính
    protected $primaryKey = 'id';

    // Không có timestamps (vì migration không có created_at, updated_at)
    public $timestamps = false;

    // Các cột cho phép gán hàng loạt
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
        'deleted_at'
    ];
    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    /**
     * Quan hệ: Sản phẩm thuộc về một thương hiệu
     */
    public function thuonghieu()
    {
        return $this->belongsTo(ThuongHieuModel::class, 'id_thuonghieu');
    }

    /**
     * Quan hệ: Sản phẩm có nhiều biến thể (bienthe)
     */
    public function bienthe()
    {
        return $this->hasMany(BientheModel::class, 'id_sanpham');
    }

    /**
     * Quan hệ: Sản phẩm có thể thuộc nhiều danh mục (nếu có bảng trung gian)
     * (Tùy bạn có bảng `sanpham_danhmuc` hay không)
     */
    public function danhmuc()
    {
        return $this->belongsToMany(DanhmucModel::class, 'danhmuc_sanpham', 'id_sanpham', 'id_danhmuc');
    }

    public function hinhanhsanpham()
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

    /**
     * Tăng lượt xem sản phẩm
     */
    public function tangLuotXem()
    {
        $this->increment('luotxem');
    }

    /**
     * Kiểm tra sản phẩm có đang hoạt động không
     */
    public function getDangHoatDongAttribute()
    {
        return $this->trangthai === 'Công khai';
    }

    /**
     * Lấy mô tả ngắn (rút gọn nội dung)
     */
    public function getMoTaNganAttribute()
    {
        return substr(strip_tags($this->mota), 0, 100) . '...';
    }

    /**
     * //Model động lấy field enum động
     */
    public static function getEnumValues($column)
    {
        $table = (new static)->getTable();

        $result = DB::select("SHOW COLUMNS FROM {$table} WHERE Field = '{$column}'");

        preg_match_all("/'([^']+)'/", $result[0]->Type, $matches);

        return $matches[1];
    }
}
