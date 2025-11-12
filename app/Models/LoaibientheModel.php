<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LoaibientheModel extends Model
{
    use HasFactory;

    // Tên bảng trong database
    protected $table = 'loaibienthe';

    // Khóa chính của bảng
    protected $primaryKey = 'id';

    // Tắt timestamps vì trong migration không có created_at và updated_at
    public $timestamps = false;

    // Các cột được phép gán hàng loạt
    protected $fillable = [
        'ten',
        'trangthai',
    ];

    // Giá trị mặc định cho model
    protected $attributes = [
        'trangthai' => 'Hiển thị',
    ];

    public function bienthe()
    {
        return $this->hasMany(BientheModel::class, 'id_loaibienthe');
    }
    public function sanpham()
    {
        return $this->belongsToMany(SanphamModel::class, 'bienthe', 'id_loaibienthe', 'id_sanpham');
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

    /**
     * Xóa Cứng luốn các bảng liên quan đến sanpham, thủ công mới 4 bảng, database chỉnh ondelete cascade không cần thủ công quá nhiều
     */
    protected static function booted()
    {
        static::deleting(function ($loaibienthe) {
            // 1️⃣ Lấy danh sách id sản phẩm liên quan đến loại biến thể này
            $idSanPhams = BientheModel::where('id_loaibienthe', $loaibienthe->id)
                ->pluck('id_sanpham')
                ->unique()
                ->toArray();

            // 2️⃣ Xóa cứng các bản ghi trong bảng bienthe
            BientheModel::withTrashed()
                ->where('id_loaibienthe', $loaibienthe->id)
                ->forceDelete();

            // 3️⃣ Nếu có sản phẩm liên quan thì xóa luôn ảnh và sản phẩm
            if (!empty($idSanPhams)) {

                // 🖼️ Xóa cứng hình ảnh sản phẩm liên quan
                HinhanhSanphamModel::withTrashed()
                    ->whereIn('id_sanpham', $idSanPhams)
                    ->forceDelete();

                // 🛒 Xóa cứng sản phẩm
                SanphamModel::withTrashed()
                    ->whereIn('id', $idSanPhams)
                    ->forceDelete();
            }
        });
    }
}
