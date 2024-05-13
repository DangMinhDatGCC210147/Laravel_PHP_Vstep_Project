<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
}
