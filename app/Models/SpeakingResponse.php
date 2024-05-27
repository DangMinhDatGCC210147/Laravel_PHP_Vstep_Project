<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeakingResponse extends Model
{
    use HasFactory;
    protected $fillable = ['skill_id', 'student_id', 'question_id','audio_response'];

    public function testSkill()
    {
        return $this->belongsTo(TestSkill::class);
    }
}
