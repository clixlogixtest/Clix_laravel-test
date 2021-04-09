<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class EmailVerificationController extends Controller
{
	public function verifyUser($token)
	{
		$user = User::where('email_verify_token', $token)->first();
		
		if($user)
		{
			if(empty($user->email_verified_at)) 
			{
				$user->email_verified_at = Date::now();
				$user->email_verify_token = null;
				$user->save();
				$message = "Your e-mail is verified. You can now login.";
			} 
			else 
			{
				$message = "Your e-mail is already verified. You can now login.";
			}

			return redirect('/')->with('success', $message);
		} 
		else 
		{
			return redirect('/')->with('error', "Sorry your email cannot be identified.");
		}
	}
}
