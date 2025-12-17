<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HinhanhsanphamModel extends Model
{
    use HasFactory, SoftDeletes;

    // Tên bảng trong database
    protected $table = 'hinhanh_sanpham';

    // Khóa chính
    protected $primaryKey = 'id';

    // Các cột cho phép gán hàng loạt
    protected $fillable = [
        'id_sanpham',
        'hinhanh',
        'trangthai',
        'deleted_at'
    ];

    // Giá trị mặc định
    protected $attributes = [
        'trangthai' => 'Hiển thị',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    // Không cần timestamps vì migration không có created_at / updated_at
    public $timestamps = false;

    /**
     * Quan hệ: Mỗi hình ảnh thuộc về 1 sản phẩm
     */
    public function sanpham()
    {
        return $this->belongsTo(SanphamModel::class, 'id_sanpham', 'id');
    }

    /**
     * Lấy thông tin ảnh từ URL (width, height, mime)
     * Sau bước này, mỗi $item sẽ có: $item->image_meta
     */
    public function getImageMetaAttribute()
    {
        if (!$this->hinhanh) {
            return null;
        }

        try {
            $info = @getimagesize($this->hinhanh);

            if (!$info) return null;

            return [
                'width'  => $info[0],
                'height' => $info[1],
                'mime'   => $info['mime'],
                'type'   => image_type_to_extension($info[2], false), // jpg, png, webp
                // 'type'   => image_type_to_extension($info[2], true),
            ];
        } catch (\Exception $e) {
            return null;
        }
    }
}
