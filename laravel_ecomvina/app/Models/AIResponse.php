<?php
// app/Models/AIResponse.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AIResponse extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ai_responses';

    protected $fillable = ['intent_id', 'response', 'priority', 'active'];

    public function intent(): BelongsTo
    {
        return $this->belongsTo(AIIntent::class);
    }
}
