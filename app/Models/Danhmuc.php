<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Danhmuc extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'danh_muc';
    public $timestamps = true;

    // Chỉ cần cột cho phép mass assignment
    protected $fillable = ['ten', 'trangthai'];

    // Cast datetime
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Trả về nhãn tiếng Việt cho trạng thái
    public function getTrangthaiLabelAttribute(): string
    {
        return match ($this->trangthai) {
            'hoat_dong' => 'Hoạt động',
            'ngung_hoat_dong' => 'Ngừng hoạt động',
            'bi_khoa' => 'Bị khóa',
            'cho_duyet' => 'Chờ duyệt',
            default => 'Không xác định',
        };
    }

    // Nếu sản phẩm có nhiều danh mục (many-to-many)
    public function sanphams()
    {
        return $this->belongsToMany(Sanpham::class, 'sanpham_danhmuc', 'id_danhmuc', 'id_sanpham');
    }

    // Nếu sản phẩm chỉ thuộc 1 danh mục thì thay bằng:
    // public function sanphams()
    // {
    //     return $this->hasMany(Sanpham::class, 'danh_muc_id');
    // }
}
