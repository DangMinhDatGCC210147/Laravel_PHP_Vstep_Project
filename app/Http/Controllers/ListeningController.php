<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use App\Models\ReadingsAudio;
use App\Models\Test;
use App\Models\TestSkill;
use Illuminate\Http\Request;

class ListeningController extends Controller
{
    public function storeListening(Request $request, Test $test_slug, $skill_id)
    {
        try {
            // Process each audio part and associated questions
            $audioPaths = [];
            for ($part = 1; $part <= 3; $part++) {
                if ($request->hasFile("audio_file.$part")) {
                    $audioPath = $request->file("audio_file.$part")->store('public/audios');
                    $audioPaths[$part] = $audioPath;
                }
            }
            // Determine the range of question numbers for each part
            $partRanges = [
                1 => ['start' => 1, 'end' => 8],
                2 => ['start' => 9, 'end' => 20],
                3 => ['start' => 21, 'end' => 35]
            ];

            // Save each part's questions
            foreach ($partRanges as $part => $range) {
                $listeningAudio = new ReadingsAudio();
                $listeningAudio->reading_audio_file = $audioPaths[$part];
                $listeningAudio->test_skill_id = $skill_id;
                $listeningAudio->save();
                for ($q = $range['start']; $q <= $range['end']; $q++) {
                    $questionData = $request->questions[$q];

                    $question = new Question();
                    $question->test_skill_id = $skill_id;
                    $question->reading_audio_id = $listeningAudio->id;
                    $question->question_number = $q;
                    $question->part_name = "Part " . $part;
                    $question->question_text = $questionData['text'];
                    $question->question_type = 'Multiple Choice Listening';
                    $question->correct_answer = $questionData['options'][$questionData['correct_answer']];
                    $question->save();

                    // Save options for the question
                    foreach ($questionData['options'] as $index => $optionText) {
                        $option = new Option();
                        $option->question_id = $question->id;
                        $option->option_text = $optionText;
                        $option->save();
                    }
                }
            }

            return redirect()->route('testSkills.show', ['test_slug' => $test_slug->slug])
                ->with('success', 'Listening parts and questions saved successfully!');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->withErrors('Error saving the listening parts: ' . $e->getMessage());
        }
    }



    public function updateListening()
    {
    }
}