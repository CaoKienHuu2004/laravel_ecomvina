<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Session extends Model
{
    use HasFactory;

    // Khai báo tên bảng (vì mặc định Laravel suy ra "sessions" thì vẫn đúng,
    // nhưng mình ghi rõ cho chắc chắn)
    protected $table = 'sessions';

    // Vì bảng sessions dùng string id làm khóa chính → cần chỉnh lại
    protected $primaryKey = 'id';
    public $incrementing = false; // id không phải auto increment
    protected $keyType = 'string'; // id dạng string (không phải int)

    // Cho phép fillable
    protected $fillable = [
        'id',
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity',

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Không có cột created_at, updated_at → tắt timestamps
    public $timestamps = false;

    /**
     * Liên kết với model NgườiDung
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'user_id');
    }
}
