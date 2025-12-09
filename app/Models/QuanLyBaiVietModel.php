<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuanLyBaiVietModel extends Model
{
    use HasFactory;

    protected $table = 'baiviet';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id_nguoidung',
        'tieude',
        'slug',
        'noidung',
        'hinhanh',
        'trangthai',
        'luotxem',
    ];

    protected $attributes = [
        'trangthai' => 'Công khai',
        'luotxem'   => 0,
    ];

    protected $casts = [
        'luotxem' => 'integer',
    ];

    // Quan hệ với người dùng
    public function nguoidung()
    {
        return $this->belongsTo(NguoidungModel::class, 'id_nguoidung', 'id');
    }

    // Scope lọc bài công khai
    public function scopeCongKhai($query)
    {
        return $query->where('trangthai', 'Công khai');
    }

    // Tăng lượt xem
    public function tangLuotXem()
    {
        $this->increment('luotxem');
    }
}
