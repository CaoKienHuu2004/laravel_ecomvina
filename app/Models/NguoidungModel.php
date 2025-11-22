<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NguoidungModel extends Authenticatable
// class NguoidungModel extends User
{
    use HasFactory, SoftDeletes;

    protected $table = 'nguoidung';

    public $timestamps = false;

    protected $fillable = [
        'username',
        'email',
        'password',
        'sodienthoai',
        'hoten',
        'gioitinh',
        'ngaysinh',
        'avatar',
        'vaitro',
        'trangthai',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'ngaysinh'   => 'date:Y-m-d',
        'password'   => 'hashed',
    ];

    //============================================================
    // MỐI QUAN HỆ (RELATIONSHIPS)
    //===========================================================
    public function diachi(): HasMany
    {
        return $this->hasMany(DiaChiGiaoHangModel::class, 'id_nguoidung');
    }


    public function baiviet(): HasMany
    {
        return $this->hasMany(BaivietModel::class, 'id_nguoidung');
    }
    public function yeuthich()
    {
        return $this->hasMany(YeuthichModel::class, 'id_nguoidung', 'id');
    }
    public function giohang(): HasMany
    {
        return $this->hasMany(GiohangModel::class, 'id_nguoidung');
    }
    public function thongbao(): HasMany
    {
        return $this->hasMany(ThongbaoModel::class, 'id_nguoidung');
    }
    public function danhgia(): HasMany
    {
        return $this->hasMany(DanhgiaModel::class, 'id_nguoidung');
    }
}
