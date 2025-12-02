<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class QuatangsukienModel extends Model
{
    use HasFactory, SoftDeletes;

    // Tên bảng
    protected $table = 'quatang_sukien';

    // Khóa chính
    protected $primaryKey = 'id';

    public $timestamps = false;

    // Các cột được phép gán hàng loạt
    protected $fillable = [
        'id_bienthe',
        'id_chuongtrinh',
        // 'dieukien', // edit và đã delete
        'dieukiensoluong', // new
        'dieukiengiatri', // new
        'tieude',
        'slug', // new
        'thongtin',
        'hinhanh',
        'luotxem',
        'ngaybatdau',
        'ngayketthuc',
        'trangthai',
        'deleted_at'
    ];
    protected $casts = [
        'deleted_at' => 'datetime',
    ];
    protected $dates = [
        'ngaybatdau',
        'ngayketthuc',
    ];
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * 🔗 Quan hệ N-1 với bảng chuongtrinh
     * Mỗi quatang_sukien thuộc về 1 chương trình.
     */
    public function chuongtrinh()
    {
        return $this->belongsTo(ChuongTrinhModel::class, 'id_chuongtrinh');
    }

    /**
     * 🔗 Quan hệ N-1 với bảng bienthe
     * Mỗi quatang_sukien áp dụng cho 1 biến thể sản phẩm cụ thể.
     */
    public function bienthe()
    {
        return $this->belongsTo(BientheModel::class, 'id_bienthe');
    }

    /**
     * 🧠 Hàm tiện ích: Lấy danh sách quà tặng đang hoạt động
     */
    public static function hoatDong()
    {
        return self::where('trangthai', 'Hoạt động')
            ->whereDate('ngaybatdau', '<=', now())
            ->whereDate('ngayketthuc', '>=', now())
            ->get();
    }

    /**
     * 🧠 Hàm tiện ích: Kiểm tra xem chương trình có đang trong thời gian hiệu lực không
     */
    public function dangHieuLuc()
    {
        return $this->trangthai === 'Hoạt động'
            && now()->between($this->ngaybatdau, $this->ngayketthuc);
    }
    public function scopeHienThi($query)
    {
        return $query->where('trangthai', 'Hiển thị');
    }

    // Scope: chỉ lấy quà tặng đang tạm ẩn
    public function scopeTamAn($query)
    {
        return $query->where('trangthai', 'Tạm ẩn');
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
