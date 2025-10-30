<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TukhoaModel extends Model
{
    // Kích hoạt tính năng Factory nếu bạn muốn dùng để tạo dữ liệu mẫu
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu (đã được định nghĩa trong migration)
    protected $table = 'tukhoa';

    // Xác định các cột có thể được gán giá trị hàng loạt (Mass Assignment)
    protected $fillable = [
        'tukhoa',
        'luottruycap',
    ];
}
