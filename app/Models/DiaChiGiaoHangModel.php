<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class DiaChiGiaoHangModel extends Model
{
    use HasFactory, SoftDeletes;

    // Tên bảng tương ứng trong database
    protected $table = 'diachi_giaohang';

    // Khóa chính
    protected $primaryKey = 'id';

    // Các cột có thể gán giá trị hàng loạt
    protected $fillable = [
        'id_nguoidung',
        'hoten',
        'sodienthoai',
        'diachi',
        'tinhthanh',
        'trangthai',
        'deleted_at',
    ];

    public $timestamps = false;

    // Ép kiểu dữ liệu nếu cần
    protected $casts = [
        'id_nguoidung' => 'integer',
        'deleted_at'   => 'datetime',
    ];
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * Quan hệ với bảng NguoiDung (1 người dùng có nhiều địa chỉ giao hàng)
     */
    public function nguoidung()
    {
        return $this->belongsTo(NguoidungModel::class, 'id_nguoidung');
    }

    /**
     * Scope lọc theo trạng thái "Mặc định"
     */
    public function scopeMacDinh($query)
    {
        return $query->where('trangthai', 'Mặc định');
    }

    /**
     * Scope lọc theo trạng thái "Khác"
     */
    public function scopeKhac($query)
    {
        return $query->where('trangthai', 'Khác');
    }

    /**
     * Scope lọc theo tỉnh/thành
     */
    public function scopeTheoTinh($query, $tinh)
    {
        return $query->where('tinhthanh', $tinh);
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
