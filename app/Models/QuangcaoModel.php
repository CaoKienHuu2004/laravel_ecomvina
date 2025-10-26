<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuangcaoModel extends Model
{
    use HasFactory;
    protected $table = 'quangcao';

    protected $fillable = [
        'vitri',
        'hinhanh',
        'lienket',
        'mota',
        'trangthai'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
