<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuKhoa extends Model
{
    use HasFactory;

    protected $table = 'tu_khoa';
    // public $timestamps = true;

    // Các cột được phép mass assignment
    protected $fillable = [
        'dulieu',
        'soluot',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Accessor: nhãn mô tả số lượt (ví dụ để hiển thị đẹp hơn)
    // public function getLuotLabelAttribute(): string
    // {
    //     return number_format($this->soluot) . ' lượt tìm kiếm';
    // }
}
