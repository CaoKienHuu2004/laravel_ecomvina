<?php
// app/Models/AIIntent.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AIIntent extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ai_intents';

    protected $fillable = ['name', 'description'];

    public function trainingData(): HasMany
    {
        return $this->hasMany(AITrainingData::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(AIResponse::class);
    }
}
