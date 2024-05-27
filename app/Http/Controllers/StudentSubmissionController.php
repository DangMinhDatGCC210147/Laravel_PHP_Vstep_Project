<?php

namespace App\Http\Controllers;

use App\Models\ListeningResponses;
use App\Models\SpeakingResponse;
use App\Models\ReadingResponses;
use App\Models\WritingResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentSubmissionController extends Controller
{
    public function saveListening(Request $request) {
        $validated = $request->validate([
            'skill_id' => 'required|integer',
            'responses' => 'required|array',
            'responses.*' => 'required|string|max:255',
        ]);
        $studentId = auth()->id(); 
        // Duyệt qua mỗi câu trả lời trong mảng responses
        foreach ($request->responses as $questionId => $response) {
            ListeningResponses::updateOrCreate(
                [
                    'skill_id' => $request->skill_id,
                    'student_id' => $studentId,
                    'question_id' => $questionId
                ],
                ['text_response' => $response]
            );
        }
    
        return back()->with('success', 'The reading skill answer has been saved successfully.');
    }
    
    public function saveSpeaking(Request $request) {
        // Xử lý lưu dữ liệu Speaking
    }
    
    public function saveReading(Request $request) {
        $validated = $request->validate([
            'skill_id' => 'required|integer',
            'responses' => 'required|array',
            'responses.*' => 'required|string',
        ]);
        $studentId = auth()->id(); 
        // Duyệt qua mỗi câu trả lời trong mảng responses
        foreach ($request->responses as $questionId => $response) {
            ReadingResponses::updateOrCreate(
                [
                    'skill_id' => $request->skill_id,
                    'student_id' => $studentId,
                    'question_id' => $questionId
                ],
                ['text_response' => $response]
            );
        }
    
        return back()->with('success', 'The reading skill answer has been saved successfully.');
    }     
    
    public function saveWriting(Request $request) {
        // Xử lý lưu dữ liệu Writing
    }
    
}
