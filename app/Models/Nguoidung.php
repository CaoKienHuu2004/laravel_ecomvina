<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; //Cho phép gửi thông báo đến model (mail, database, SMS…)
use Laravel\Sanctum\HasApiTokens; //Cho phép model tạo và quản lý API token (dùng Sanctum)
use Laravel\Fortify\TwoFactorAuthenticatable;

use App\Notifications\ResetPasswordNotification;

use Laravel\Jetstream\HasProfilePhoto; // của jetstream


class Nguoidung extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, TwoFactorAuthenticatable;
    use HasProfilePhoto; // của jetstream
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

    ///////////// vì jetstem nó có UI để field là name
    public function getNameAttribute()
    {
        return $this->hoten; // Khi gọi $user->name → nó sẽ trả về hoten
    }
    public function setNameAttribute($value)
    {
        $this->attributes['hoten'] = $value; // Nếu bạn muốn khi gán $user->name = '...' thì nó update hoten luôn
    }
    ///////////// vì jetstem nó có UI để field là name

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
    public function session()
    {
        return $this->hasMany(Session::class, 'user_id');
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
    public function isUser(): bool
    {
        return $this->hasRole('user');
    }
    public function isAnonymous(): bool
    {
        return $this->hasRole('anonymous');
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
    /**
     * The accessors to append to the model's array form. của jetstream
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
}
