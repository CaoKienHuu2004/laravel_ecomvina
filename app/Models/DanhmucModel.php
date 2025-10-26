<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\SanphamModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DanhmucModel extends Model
{
    use HasFactory; // thiếu xóa mềm rồi
    protected $table = 'danhmuc';
    protected $primaryKey = 'id';
    protected $fillable = ['ten', 'slug','logo','parent','created_at','updated_at'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function sanpham(): BelongsToMany
    {
        return $this->belongsToMany(SanphamModel::class,'danhmuc_sanpham', 'id_danhmuc', 'id_sanpham');
    }

    // Danh mục cha
    public function danhmucha()
    {
        return $this->belongsTo(DanhmucModel::class, 'parent');
    }

    // Danh mục con
    public function danhmuccon()
    {
        return $this->hasMany(DanhmucModel::class, 'parent');
    }



}
