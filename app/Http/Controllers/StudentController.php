<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Test;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $request->validate([
            'image' => 'required|image|max:5048',
            'accountId' => 'required',
        ]);

        $latestStudentEntry = Student::where('user_id', $request->accountId)
            ->orderByDesc('created_at')
            ->first();

        if (!$latestStudentEntry) {
            return response()->json(['error' => 'No student found with the given user ID'], 404);
        }
        if ($latestStudentEntry->image_file) {
            Storage::disk('public')->delete(str_replace('storage/', '', $latestStudentEntry->image_file));
        }

        $path = $request->file('image')->store('students', 'public');
        $url = Storage::url($path);
        // Lưu URL hình ảnh và thông tin người dùng vào database
        $student = Student::updateOrCreate(
            ['user_id' => $request->accountId], // Trường duy nhất để xác định Student
            ['image_file' => $url, 'test_id' => $latestStudentEntry->id] // Cập nhật hoặc thêm mới các trường này
        );

        return response()->json(['message' => 'Hình ảnh đã được lưu thành công!']);
    }
}
