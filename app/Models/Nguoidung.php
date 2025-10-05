<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Nguoidung extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Tên bảng trong database mà model này sẽ quản lý.
     *
     * @var string
     */
    protected $table = 'nguoi_dung'; // Hoặc 'users' tùy theo tên bảng của bạn

    /**
     * Các thuộc tính có thể được gán hàng loạt (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'vaitro',
        'password',
        'sodienthoai',
        'ngaysinh',
        'hoten',
        'gioitinh',
        'trangthai',
        'created_at',
        'updated_at',
    ];

    /**
     * Các thuộc tính nên được ẩn khi chuyển thành dạng mảng hoặc JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Các thuộc tính nên được chuyển đổi (cast) sang các kiểu dữ liệu gốc.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function diachi()
    {
        return $this->hasMany(Diachi::class,'id_nguoidung');
    }


}
