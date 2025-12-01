<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhiVanChuyenModel extends Model
{
    use HasFactory;

    // Tên bảng trong database
    protected $table = 'phivanchuyen';

    // Khóa chính của bảng
    protected $primaryKey = 'id';

    // Bảng không có timestamps (created_at, updated_at)
    public $timestamps = false;

    // Các cột có thể gán hàng loạt
    protected $fillable = [
        'ten',
        'phi',
        'trangthai',
    ];

    /**
     * Lấy danh sách các bản ghi đang hiển thị
     */
    public function scopeHienThi($query)
    {
        return $query->where('trangthai', 'hiển thị');
    }

    /**
     * Kiểm tra xem phí vận chuyển có đang hiển thị không
     */
    public function laHienThi(): bool
    {
        return $this->trangthai === 'hiển thị';
    }
}
