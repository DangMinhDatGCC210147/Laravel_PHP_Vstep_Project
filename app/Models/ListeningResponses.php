<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListeningResponses extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'skill_id',
        'student_id',
        'question_id',
        'text_response',
    ];
}
