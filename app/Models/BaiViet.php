<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaiViet extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bai_viet';

    protected $fillable = [
        'tieude',
        'mota',
        'noidung',
        'luotxem',
        'trangthai',
        'id_nguoidung',

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Quan hệ: Bài viết thuộc về một người dùng
     * (tác giả của bài viết)
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoidung');
    }

    /**
     * Scope lọc bài viết đã xuất bản
     */
    public function scopeDaXuatBan($query)
    {
        return $query->where('trangthai', 'đã xuất bản');
    }

    /**
     * Tăng lượt xem cho bài viết
     */
    public function tangLuotXem()
    {
        $this->increment('luotxem');
    }

    /**
     * Kiểm tra bài viết có được xuất bản hay chưa
     */
    public function daXuatBan()
    {
        return $this->trangthai === 'đã xuất bản';
    }
}
