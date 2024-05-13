<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WritingResponse extends Model
{
    use HasFactory;
    protected $fillable = ['skill_id', 'student_id', 'text_response'];

    // public function skillPart()
    // {
    //     return $this->belongsTo(SkillPart::class);
    // }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
