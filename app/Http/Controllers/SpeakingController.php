<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use App\Models\ReadingsAudio;
use App\Models\Test;
use App\Models\TestSkill;
use Illuminate\Http\Request;

class SpeakingController extends Controller
{
    public function storeSpeaking(Request $request, Test $test_slug, $skill_id)
    {
        try {
            // Process Part 1
            for ($i = 1; $i <= 2; $i++) {
                $readingsAudio = new ReadingsAudio([
                    'test_skill_id' => $skill_id,
                    'reading_audio_file' => $request->input("part1_question_$i")
                ]);
                $readingsAudio->save();

                $questionText = $request->input("part1_question_$i");

                $question = new Question([
                    'test_skill_id' => $skill_id,
                    'reading_audio_id' => $readingsAudio->id,
                    'question_text' => $questionText,
                    'question_type' => 'Text Speaking',
                    'question_number' => $i,
                    'part_name' => 'Part 1'
                ]);
                $question->save();
                // Save options for Part 1
                for ($j = 1; $j <= 3; $j++) {
                    $optionText = $request->input("part1_question_{$i}_option_{$j}");
                    $option = new Option([
                        'question_id' => $question->id,
                        'option_text' => $optionText
                    ]);
                    $option->save();
                }
            }

            // Process Part 2
            $readingsAudio2 = new ReadingsAudio([
                'test_skill_id' => $skill_id,
                'reading_audio_file' => $request->input("part2_text")
            ]);
            $readingsAudio2->save();

            $part2Text = $request->input('part2_text');
            $questionPart2 = new Question([
                'test_skill_id' => $skill_id,
                'reading_audio_id' => $readingsAudio2->id,
                'question_text' => $part2Text,
                'question_type' => 'Text Speaking',
                'part_name' => 'Part 2',
                'question_number' => 1  // Assuming only one question in Part 2
            ]);
            $questionPart2->save();

            // Process Part 3
            if ($request->hasFile('part3_image')) {
                $imagePath = $request->file('part3_image')->store('images', 'public');
                $listeningAudio3 = new ReadingsAudio();
                $listeningAudio3->reading_audio_file = $imagePath;
                $listeningAudio3->test_skill_id = $skill_id;
                $listeningAudio3->save();

                $questionTextPart3 = $request->input('part3_question');
                $questionPart3 = new Question([
                    'test_skill_id' => $skill_id,
                    'reading_audio_id' => $listeningAudio3->id,
                    'question_text' => $questionTextPart3,
                    'question_type' => 'Text Speaking',
                    'question_number' => 1,  // Assuming only one question in Part 3
                    'part_name' => 'Part 3',
                ]);
                $questionPart3->save();

                // Save options for Part 3
                for ($k = 1; $k <= 3; $k++) {
                    $optionTextPart3 = $request->input("part3_option_$k");
                    $option = new Option([
                        'question_id' => $questionPart3->id,
                        'option_text' => $optionTextPart3
                    ]);
                    $option->save();
                }
            }

            return redirect()->route('testSkills.show', ['test_slug' => $test_slug])
                ->with('success', 'Speaking test created successfully!');
        } catch (\Exception $e) {
            return back()->withErrors('Error saving the speaking test: ' . $e->getMessage());
        }
    }

    public function updateSpeaking(Request $request, Test $test_slug, TestSkill $skill_slug)
    {
        try {
            // Cập nhật câu hỏi Part 1
            $part1Questions = Question::where('part_name', 'Part 1')
                ->where('test_skill_id', $skill_slug->id)
                ->get(); // Lấy tất cả câu hỏi Part 1
            foreach ($part1Questions as $question) {
                $index = $question->question_number; // Giả sử question_number là thứ tự của câu hỏi
                $questionKey = "part1_question_{$index}";
                if ($request->has($questionKey)) {
                    $question->question_text = $request->input($questionKey);
                    $question->save();
                    // dd($questionKey);
                    // Cập nhật các tùy chọn
                    for ($j = 1; $j <= 3; $j++) { // Giả sử mỗi câu hỏi có tối đa 3 lựa chọn
                        $optionKey = "{$questionKey}_option_{$j}";
                        if ($request->has($optionKey)) {
                            $option = $question->options()->where('option_number', $j)->first(); // Giả sử có trường option_number
                            if ($option) {
                                $option->option_text = $request->input($optionKey);
                                $option->save();
                            }
                        }
                    }
                }
            }

            // Cập nhật câu hỏi Part 2
            $part2Question = Question::where('part_name', 'Part 2')->first();
            if ($part2Question && $request->has('part2_text')) {
                $part2Question->question_text = $request->part2_text;
                $part2Question->save();
            }

            // Cập nhật câu hỏi và hình ảnh Part 3
            $part3Question = Question::where('part_name', 'Part 3')->first();
            if ($part3Question && $request->has('part3_question')) {
                $part3Question->question_text = $request->part3_question;
                $part3Question->save();

                // Cập nhật hình ảnh nếu có
                if ($request->hasFile('part3_image')) {
                    $path = $request->file('part3_image')->store('public/images');
                    $part3Question->image = $path;
                    $part3Question->save();
                }

                // Cập nhật các tùy chọn của Part 3
                $options = $request->input('part3_option');
                foreach ($options as $k => $optionText) {
                    $option = $part3Question->options()->skip($k)->first();
                    if ($option) {
                        $option->option_text = $optionText;
                        $option->save();
                    }
                }
            }

            return redirect()->route('testSkills.show', ['test_slug' => $test_slug])->with('success', 'Speaking test updated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors('Error updating the speaking test: ' . $e->getMessage());
        }
    }
}
