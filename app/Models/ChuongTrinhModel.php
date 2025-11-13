<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChuongTrinhModel extends Model
{
    use HasFactory;

    protected $table = 'chuongtrinh';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'tieude',
        'slug',
        'hinhanh',
        'noidung',
        'trangthai',
    ];

    public static function hienThi()
    {
        return self::where('trangthai', 'Hiển thị')->get();
    }

    public static function timTheoSlug($slug)
    {
        return self::where('slug', $slug)->first();
    }

    // Quan hệ 1 - N với bảng quatang_sukien
    public function quatangsukien()
    {
        return $this->hasMany(QuatangsukienModel::class, 'id_chuongtrinh');
    }
    /**
     * Xóa Cứng luốn các bảng liên quan đến
     */
    protected static function booted()
    {
        static::deleting(function ($chuongtrinh) {
            QuatangsukienModel::withTrashed()
                    ->where('id_chuongtrinh', $chuongtrinh->id)
                    ->forceDelete();
        });
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
