<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uudaithanhvien extends Model
{
    use HasFactory;
    protected $table = "uudaithanhvien";
    protected $fillable = [
        'id',
        'hanmuc',
        'giamgia',
        'dieukien',
    ] ;
}
