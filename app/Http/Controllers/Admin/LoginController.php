<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application login.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $input = $request->all();
   
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email',$request->email)->first();

        if($user && $user->is_admin == 'yes')
        {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials,$request->filled('remember'))) 
            {
                return redirect()->route('admin.dashboard')->with('success','You are successfully logged in.');
            }
            else
            {
                return redirect()->route('admin.login')->with('error','Whoops! You have entered invalid credentials.');
            }
        }
        else
        {
            return redirect()->route('admin.login')->with('error','Whoops! Please login with admin credentials.');
        }
    }
}
