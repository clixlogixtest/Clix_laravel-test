<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function index()
    {
  		return view('admin.auth.change_password');
    }

    public function update(Request $request)
    {
    	$request->validate([
			'old_password' => 'required',
			'password' => 'required|min:8|regex:/[@$!%*#?&]/|confirmed',
			'password_confirmation' => 'required',
        ], [
        	'password.regex' => 'Password must contain at least 1 special character.'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) 
        {
            return back()->with('error', 'Old password does not match.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password successfully changed.');
    }
}
