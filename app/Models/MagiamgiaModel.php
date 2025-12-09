<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MagiamgiaModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'magiamgia';

    // Khóa chính của bảng
    protected $primaryKey = 'id';

    // Tắt tính năng timestamps nếu không dùng created_at, updated_at
    public $timestamps = false;

    // Các cột được phép gán hàng loạt
    protected $fillable = [
        'magiamgia',
        'dieukien',
        'mota',
        'giatri',
        'ngaybatdau',
        'ngayketthuc',
        'trangthai',
    ];

    // Ép kiểu dữ liệu cho các trường
    protected $casts = [
        'ngaybatdau'  => 'date',
        'ngayketthuc' => 'date',
    ];

    // Ghi chú trạng thái (nếu cần hiển thị tiếng Việt dễ đọc)
    public static $trangthaiLabels = [
        'Hoạt động'       => 'Đang hoạt động',
        'Tạm khóa'        => 'Tạm thời bị khóa',
        'Dừng hoạt động'  => 'Ngừng sử dụng',
    ];

    protected $hidden = ['deleted_at'];

    /**
     * Kiểm tra mã giảm giá còn hiệu lực hay không.
     */
    public function isValid(): bool
    {
        $today = now('Asia/Ho_Chi_Minh')->toDateString();

        return $this->trangthai === 'Hoạt động'
            && $today >= $this->ngaybatdau->toDateString()
            && $today <= $this->ngayketthuc->toDateString();
    }

    /**
     * Lấy mô tả trạng thái đầy đủ (vd: "Đang hoạt động").
     */
    public function getTrangThaiLabelAttribute(): string
    {
        return self::$trangthaiLabels[$this->trangthai] ?? $this->trangthai;
    }

    public function donhang()
    {
        return $this->hasMany(DonhangModel::class, 'id_magiamgia');
    }
}
