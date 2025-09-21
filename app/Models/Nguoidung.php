<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; //Cho phép gửi thông báo đến model (mail, database, SMS…)
use Laravel\Sanctum\HasApiTokens; //Cho phép model tạo và quản lý API token (dùng Sanctum)
use Laravel\Fortify\TwoFactorAuthenticatable;

use App\Notifications\ResetPasswordNotification;

class Nguoidung extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, TwoFactorAuthenticatable;

    /**
     * Tên bảng trong database mà model này sẽ quản lý.
     *
     * @var string
     */
    protected $table = 'nguoi_dung';

    /**
     * Các thuộc tính có thể được gán hàng loạt (mass assignable).
     */
    protected $fillable = [
        // 'usename', // chú ý: nếu muốn sửa tên thành username, hãy đồng bộ migration
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
        'deleted_at',
    ];

    /**
     * Các thuộc tính nên được ẩn khi chuyển thành dạng mảng hoặc JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * Các thuộc tính được cast sang kiểu dữ liệu gốc.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ tự hash khi set

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
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

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isAssistant(): bool
    {
        return $this->hasRole('assistant');
    }

    /**
     * Gửi thông báo xác thực email (Fortify yêu cầu).
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \Illuminate\Auth\Notifications\VerifyEmail);
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
