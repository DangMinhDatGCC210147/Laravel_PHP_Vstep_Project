<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Test;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $user_name = Session::get('user_name');
        $user_email = Session::get('user_email');
        $parts = explode('@', $user_email);
        $user_id_student = $parts[0];
        $account_id = Session::get('account_id');
        // Truyền dữ liệu đến view
        return view('students.index', [
            'user_name' => $user_name,
            'user_email' => $user_email,
            'user_id_student' => $user_id_student,
            'account_id' => $account_id
        ]);
    }

    public function store(Request $request)
    {
        dd($request->all());
        $data = $request->validate([
            'userName' => 'required',
            'userEmail' => 'required',
            'userId' => 'required',
            'accountId' => 'required',
            'imageData' => 'required'
        ]);

        // Decode the image data
        $imageData = $data['imageData'];
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $imageFile = base64_decode($imageData);

        // Generate a unique file name
        $fileName = 'capture-' . time() . '.png';
        $path = public_path('images/' . $fileName);

        // Save the image file
        file_put_contents($path, $imageFile);
        $randomTest = Test::where('test_type', 'trialTest')->inRandomOrder()->first();

        // Check if a test was found
        if (!$randomTest) {
            return response()->json(['message' => 'No trial test found'], 404);
        }
        // Save the record in your database
        $record = new Student([
            'user_id' => $data['userId'],
            'test_id' => $randomTest->id,
            'image_file' => $fileName,
        ]);
        $record->save();

        return response()->json(['message' => 'Image saved successfully']);
    }
}
