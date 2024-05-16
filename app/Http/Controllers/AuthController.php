<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function registerPost(Request $request){
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->lecturer_id = $request->lecturer_id;
        $user->password = Hash::make($request->password);
        $user->save();
        // return back()->with('success','Registered successfully');
        return redirect()->route('tableLecturer.index')->with('success', 'Registered successfully');
    }

    public function showlogin(){
        return view('students.index');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.index')->with('success', 'Login successfully!'); 
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
