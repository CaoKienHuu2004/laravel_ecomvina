<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Nguoidung extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * Tên bảng trong database mà model này sẽ quản lý.
     *
     * @var string
     */
    protected $table = 'nguoi_dung';

    /**
     * Các thuộc tính có thể được gán hàng loạt (mass assignable).
     *
     * ⚠️ Chú ý: trong migration cột là "usename" (thiếu "r").
     */
    protected $fillable = [
        'usename',
        'email',
        'password',
        'avatar',
        'hoten',
        'giotinh',
        'ngaysinh',
        'sodienthoai',
        'vaitro',
        'trangthai',
        'created_at',
        'updated_at',
    ];

    /**
     * Các thuộc tính nên được ẩn khi chuyển thành dạng mảng hoặc JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Các thuộc tính được cast sang kiểu dữ liệu gốc.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ tự hash khi set
    ];

    /**
     * Quan hệ: Người dùng có nhiều địa chỉ.
     */
    public function diachi()
    {
        return $this->hasMany(Diachi::class, 'id_nguoidung');
    }

    /**
     * Quan hệ: Người dùng có nhiều phiên đăng nhập.
     */
    public function phienDangNhap()
    {
        return $this->hasMany(PhienDangNhap::class, 'nguoi_dung_id');
    }

    /**
     * Kiểm tra vai trò người dùng.
     */
    public function hasRole(string $role): bool
    {
        return strtolower((string) ($this->vaitro ?? '')) === strtolower($role);
    }

    /**
     * Người dùng là admin?
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Người dùng là assistant?
     */
    public function isAssistant(): bool
    {
        return $this->hasRole('assistant');
    }
}
