<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class PhuongthucModel extends Model
{
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'phuongthuc';

    // Khóa chính
    protected $primaryKey = 'id';

    // Laravel sẽ tự động quản lý created_at và updated_at
    public $timestamps = true;

    // Các cột cho phép gán giá trị hàng loạt
    protected $fillable = [
        'ten',
        'maphuongthuc',
        'trangthai',
    ];

    // Ép kiểu dữ liệu cho các trường
    protected $casts = [
        'ten' => 'string',
        'maphuongthuc' => 'string',
    ];

    // Giá trị mặc định
    protected $attributes = [
        'trangthai' => 'Hoạt động',
    ];


    public function donhang(): HasMany
    {
        return $this->hasMany(DonhangModel::class, 'id_phuongthuc');
    }


    // Scope: Lấy các phương thức đang hoạt động
    public function scopeHoatDong($query)
    {
        return $query->where('trangthai', 'Hoạt động');
    }

    // Scope: Lấy các phương thức đang bị tạm khóa
    public function scopeTamKhoa($query)
    {
        return $query->where('trangthai', 'Tạm khóa');
    }

    // Scope: Lấy các phương thức đã dừng hoạt động
    public function scopeDungHoatDong($query)
    {
        return $query->where('trangthai', 'Dừng hoạt động');
    }
    /**
     * //Model động lấy field enum động
     */
    public static function getEnumValues($column)
    {
        $table = (new static)->getTable();

        $result = DB::select("SHOW COLUMNS FROM {$table} WHERE Field = '{$column}'");

        preg_match_all("/'([^']+)'/", $result[0]->Type, $matches);

        return $matches[1];
    }
}
