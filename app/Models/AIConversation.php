<?php
// app/Models/AIConversation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AIConversation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ai_conversations';

    protected $fillable = [
        'user_input', 'ai_response', 'intent', 'sentiment',
        'confidence', 'ip_address'
    ];
}
