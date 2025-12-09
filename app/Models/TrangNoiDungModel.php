<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TrangNoiDungModel extends Model
{
    use HasFactory;

    // Tên bảng trong CSDL
    protected $table = 'trang_noidung';

    // Khóa chính
    protected $primaryKey = 'id';

    protected $fillable = [
        'tieude',
        'slug',
        'mota',
        'created_at',
        'updated_at',
        'trangthai',
        'hinhanh',
    ];

    // Bỏ timestamps vì migration không có created_at và updated_at
    public $timestamps = true;

    // Giá trị mặc định
    protected $attributes = [
        'hinhanh' => 'page.jpg',
        'trangthai' => 'Hiển thị',
    ];

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
