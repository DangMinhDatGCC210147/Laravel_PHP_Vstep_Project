<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'test_id'];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }

    public function writingResponses()
    {
        return $this->hasMany(WritingResponse::class);
    }
}
