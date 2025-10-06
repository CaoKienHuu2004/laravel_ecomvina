<?php
// app/Models/AITrainingData.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AITrainingData extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'ai_training_data';

    protected $fillable = ['intent_id', 'text', 'metadata'];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function intent(): BelongsTo
    {
        return $this->belongsTo(AIIntent::class);
    }
}
