<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThongbaoModel extends Model
{
    use HasFactory,SoftDeletes;

    // Tên bảng
    protected $table = 'thongbao';

    // Khóa chính
    protected $primaryKey = 'id';

    // Tự động quản lý created_at và updated_at
    public $timestamps = true;

    // Các cột cho phép gán hàng loạt
    protected $fillable = [
        'id_nguoidung',
        'tieude',
        'noidung',
        'lienket',
        'trangthai',
    ];

    // Giá trị mặc định
    protected $attributes = [
        'trangthai' => 'Chưa đọc',
    ];

    // Ép kiểu dữ liệu
    protected $casts = [
        'id_nguoidung' => 'integer',
        'tieude' => 'string',
        'noidung' => 'string',
        'lienket' => 'string',
    ];

    // Quan hệ: Một thông báo thuộc về một người dùng
    public function nguoidung()
    {
        return $this->belongsTo(NguoidungModel::class, 'id_nguoidung');
    }

    // Scope: lấy thông báo chưa đọc
    public function scopeChuaDoc($query)
    {
        return $query->where('trangthai', 'Chưa đọc');
    }

    // Scope: lấy thông báo đã đọc
    public function scopeDaDoc($query)
    {
        return $query->where('trangthai', 'Đã đọc');
    }

    // Scope: lấy thông báo bị tạm ẩn
    public function scopeTamAn($query)
    {
        return $query->where('trangthai', 'Tạm ẩn');
    }

    // Đánh dấu thông báo là đã đọc
    public function danhDauDaDoc()
    {
        $this->update(['trangthai' => 'Đã đọc']);
    }
}
