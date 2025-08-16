<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loaibienthe extends Model
{
    protected $table = 'loai_bienthe';
    protected $fillable = ['ten'];

    use HasFactory;
}
