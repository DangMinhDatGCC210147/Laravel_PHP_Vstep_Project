<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingsAudio extends Model
{
    use HasFactory;

    protected $table = 'readings_audios';
    protected $fillable = ['test_skill_id', 'reading_audio_file'];

    public function testSkill()
    {
        return $this->belongsTo(TestSkill::class);
    }
    
    public function questions() {
        return $this->hasMany(Question::class);
    }
}
