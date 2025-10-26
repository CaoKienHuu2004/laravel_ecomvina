<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoaibientheModel extends Model
{
    use HasFactory; /// thieu xóa mềm rồi

    protected $table = 'loaibienthe';
    protected $fillable = [
        'ten',
        'trangthai',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function bienthe(): HasMany
    {
        return $this->hasMany(BientheModel::class, 'id_loaibienthe');
    }
}
