<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ThongbaoModel extends Model
{
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'thongbao';

    // Khóa chính
    protected $primaryKey = 'id';

    // Bỏ timestamps vì migration không có created_at và updated_at
    public $timestamps = true;
    // public $timestamps = false;

    // Các cột được phép gán giá trị hàng loạt
    protected $fillable = [
        'id_nguoidung',
        'tieude',
        'noidung',
        'lienket',
        'loaithongbao', //new
        'trangthai',
        'created_at', //new
        'updated_at', //new
    ];

    /**
     * Quan hệ: Thông báo thuộc về 1 người dùng
     */
    public function nguoidung()
    {
        return $this->belongsTo(NguoidungModel::class, 'id_nguoidung', 'id');
    }

    /**
     * Hàm tiện ích: đánh dấu đã đọc
     */
    public function danhDauDaDoc()
    {
        $this->trangthai = 'Đã đọc';
        $this->save();
    }

    /**
     * Hàm tiện ích: lấy tất cả thông báo chưa đọc của 1 người dùng
     */
    public static function chuaDocTheoNguoiDung($idNguoiDung)
    {
        return self::where('id_nguoidung', $idNguoiDung)
            ->where('trangthai', 'Chưa đọc')
            ->get();
    }
    // Scope: lấy thông báo bị tạm ẩn
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
