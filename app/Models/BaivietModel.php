<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaivietModel extends Model
{
    use HasFactory;

    // Tên bảng tương ứng trong database
    protected $table = 'baiviet';

    // Khóa chính của bảng
    protected $primaryKey = 'id';

    // Các cột có thể gán hàng loạt
    protected $fillable = [
        'id_nguoidung',
        'tieude',
        'slug',
        'noidung',
        'luotxem',
        'hinhanh',
        'trangthai',
        'created_at', //new
        'updated_at', //new
    ];

    // Cho phép Laravel tự động xử lý created_at, updated_at, deleted_at
    // public $timestamps = false;
    public $timestamps = true;

    // Kiểu dữ liệu cho từng cột (tùy chọn, giúp cast dữ liệu chính xác hơn)
    protected $casts = [
        'luotxem' => 'integer',
        // 'created_at' => 'datetime',
        // 'updated_at' => 'datetime',
        // 'deleted_at' => 'datetime',
    ];

    // Mặc định giá trị khi tạo mới bài viết
    protected $attributes = [
        'trangthai' => 'Hiển thị',
        'luotxem' => 0,
    ];

    // Quan hệ: Một bài viết thuộc về một người dùng
    public function nguoidung()
    {
        return $this->belongsTo(NguoidungModel::class, 'id_nguoidung', 'id');
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
