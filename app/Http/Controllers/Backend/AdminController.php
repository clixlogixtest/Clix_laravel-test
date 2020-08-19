<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\administrator;
use Illuminate\Http\Request;
use Auth;
use View;
use Storage;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\User;
use App\PasswordReset;
use Validator;

class AdminController extends Controller
{

    /* Dashboard page
    * It is also use to check login on Dashboard. 
    * If user login then it return on home page. 
    */
	public function index(Request $request){
        /*$value = session('id');
        if(!$value){
           return redirect()->guest(route( 'admin.login' ));
        }*/

        if (Auth::check() || Auth::viaRemember()) {

        

            $user = Auth::user(); //print_r($user);
            $data = $request->session()->all();
            return view::make('/pages/dashboard-modern', $data);

        }else{

            return redirect()->guest(route( 'admin.login' ));
        }

        
	}
    
    /* It is use to show login form.
    */
    public function showLoginForm()
    {
    	$pageConfigs = ['bodyCustomClass' => 'login-bg', 'isCustomizer' => false];
        return view('pages.user-login', ['pageConfigs' => $pageConfigs]);
    }
    
    /* It is use to login user by its user email and password.
    *  It is use to login for all role.
    */
    public function login( Request $request )
    {   
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required|min:8|max:20'
        ]);

        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator); 
        }    
        
        if ( Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'global_administrator'], ($request->remember == 'on') ? true : false) ) {
        	$user = Auth::user();  

            

            $request->session()->put('id', $user->id);
            $request->session()->put('name', $user->first_name." ".$user->surname);
            $request->session()->put('first_name', $user->first_name);
            $request->session()->put('surname', $user->surname);
            $request->session()->put('profile_pic', $user->profile_pic);
            $request->session()->put('role', $user->contact_number);
            $request->session()->put('email', $user->email);
            $request->session()->put('organisation_id', $user->organisation_id);
            
  	
    	    return redirect()->guest(route('admin.dashboard'));
        }else if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'organisation_administrator'], ($request->remember == 'on') ? true : false)){

            $user = Auth::user();  
            
            $org = DB::Table('organisations')->select('image')->where('organisation_id', '=', $user->organisation_id)->get();
            //print_r($org); echo $org['0']->image; die();

            $request->session()->put('id', $user->id);
            $request->session()->put('name', $user->first_name." ".$user->surname);
            $request->session()->put('first_name', $user->first_name);
            $request->session()->put('surname', $user->surname);
            $request->session()->put('profile_pic', $user->profile_pic);
            $request->session()->put('role', $user->contact_number);
            $request->session()->put('email', $user->email);
            $request->session()->put('organisation_id', $user->organisation_id);
            $request->session()->put('organisation_image', $org['0']->image);

            return redirect()->guest(route('admin.dashboard'));

        }else if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'competition_administrator'], ($request->remember == 'on') ? true : false)){

            $user = Auth::user();  

            $org = DB::Table('organisations')->select('image')->where('organisation_id', '=', $user->organisation_id)->get();

            $request->session()->put('id', $user->id);
            $request->session()->put('name', $user->first_name." ".$user->surname);
            $request->session()->put('first_name', $user->first_name);
            $request->session()->put('surname', $user->surname);
            $request->session()->put('profile_pic', $user->profile_pic);
            $request->session()->put('role', $user->contact_number);
            $request->session()->put('email', $user->email);
            $request->session()->put('organisation_id', $user->organisation_id);
            $request->session()->put('organisation_image', $org['0']->image);

            return redirect()->guest(route('admin.dashboard'));

        }else if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'user_administrator'], ($request->remember == 'on') ? true : false)){

            $user = Auth::user(); 

            $org = DB::Table('organisations')->select('image')->where('organisation_id', '=', $user->organisation_id)->get(); 

            $request->session()->put('id', $user->id);
            $request->session()->put('name', $user->first_name." ".$user->surname);
            $request->session()->put('first_name', $user->first_name);
            $request->session()->put('surname', $user->surname);
            $request->session()->put('profile_pic', $user->profile_pic);
            $request->session()->put('role', $user->contact_number);
            $request->session()->put('email', $user->email);
            $request->session()->put('organisation_id', $user->organisation_id);
            $request->session()->put('organisation_image', $org['0']->image);

            return redirect()->guest(route('admin.dashboard'));

        }else if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'prize_administrator'], ($request->remember == 'on') ? true : false)){

            $user = Auth::user();  

            $org = DB::Table('organisations')->select('image')->where('organisation_id', '=', $user->organisation_id)->get(); 

            $request->session()->put('id', $user->id);
            $request->session()->put('name', $user->first_name." ".$user->surname);
            $request->session()->put('first_name', $user->first_name);
            $request->session()->put('surname', $user->surname);
            $request->session()->put('profile_pic', $user->profile_pic);
            $request->session()->put('role', $user->contact_number);
            $request->session()->put('email', $user->email);
            $request->session()->put('organisation_id', $user->organisation_id);
            $request->session()->put('organisation_image', $org['0']->image);

            return redirect()->guest(route('admin.dashboard'));

        }else if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'competition_administrator,user_administrator,prize_administrator'], ($request->remember == 'on') ? true : false)){

            $user = Auth::user();  

            $org = DB::Table('organisations')->select('image')->where('organisation_id', '=', $user->organisation_id)->get();

            $request->session()->put('id', $user->id);
            $request->session()->put('name', $user->first_name." ".$user->surname);
            $request->session()->put('first_name', $user->first_name);
            $request->session()->put('surname', $user->surname);
            $request->session()->put('profile_pic', $user->profile_pic);
            $request->session()->put('role', $user->contact_number);
            $request->session()->put('email', $user->email);
            $request->session()->put('organisation_id', $user->organisation_id);

            $request->session()->put('organisation_image', $org['0']->image);

            return redirect()->guest(route('admin.dashboard'));

        }else if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'competition_administrator,user_administrator'], ($request->remember == 'on') ? true : false)){

            $user = Auth::user();  

            $org = DB::Table('organisations')->select('image')->where('organisation_id', '=', $user->organisation_id)->get();

            $request->session()->put('id', $user->id);
            $request->session()->put('name', $user->first_name." ".$user->surname);
            $request->session()->put('first_name', $user->first_name);
            $request->session()->put('surname', $user->surname);
            $request->session()->put('profile_pic', $user->profile_pic);
            $request->session()->put('role', $user->contact_number);
            $request->session()->put('email', $user->email);
            $request->session()->put('organisation_id', $user->organisation_id);
            $request->session()->put('organisation_image', $org['0']->image);

            return redirect()->guest(route('admin.dashboard'));

        }else if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'competition_administrator,prize_administrator'], ($request->remember == 'on') ? true : false)){

            $user = Auth::user();  

            $org = DB::Table('organisations')->select('image')->where('organisation_id', '=', $user->organisation_id)->get();

            $request->session()->put('id', $user->id);
            $request->session()->put('name', $user->first_name." ".$user->surname);
            $request->session()->put('first_name', $user->first_name);
            $request->session()->put('surname', $user->surname);
            $request->session()->put('profile_pic', $user->profile_pic);
            $request->session()->put('role', $user->contact_number);
            $request->session()->put('email', $user->email);
            $request->session()->put('organisation_id', $user->organisation_id);
            $request->session()->put('organisation_image', $org['0']->image);

            return redirect()->guest(route('admin.dashboard'));

        }else if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'user_administrator,prize_administrator'], ($request->remember == 'on') ? true : false)){

            $user = Auth::user();  

            $org = DB::Table('organisations')->select('image')->where('organisation_id', '=', $user->organisation_id)->get();

            $request->session()->put('id', $user->id);
            $request->session()->put('name', $user->first_name." ".$user->surname);
            $request->session()->put('first_name', $user->first_name);
            $request->session()->put('surname', $user->surname);
            $request->session()->put('profile_pic', $user->profile_pic);
            $request->session()->put('role', $user->contact_number);
            $request->session()->put('email', $user->email);
            $request->session()->put('organisation_id', $user->organisation_id);
            $request->session()->put('organisation_image', $org['0']->image);

            return redirect()->guest(route('admin.dashboard'));

        }
            return redirect()->back()->withInput()->withErrors(["error" => "The User Name or Password you have entered didn't match. Please enter valid Admin credential."]); 
        
    }


    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->guest(route( 'admin.login' ));
    }
    
    /**
     * Reset password sending mail form according to user email id.
     *
    */
    public function resetPassword(Request $request)
    {
        $pageConfigs = ['bodyCustomClass' => 'forgot-bg', 'isCustomizer' => false];
        return view('pages.user-forgot-password', ['pageConfigs' => $pageConfigs]);
    }
    
    /**
     * Send Reset password mail link to user email.
     *
    */
    public function resetMail(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator); 
        } 

        $user = User::where('email', $request->email)->first();
        if(!$user){
            return redirect()->back()->withInput()->withErrors(['error'=>'We cant find a user with that e-mail address!']); 
        }
            
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => str_random(60)
             ]
        ); 

        if ($user && $passwordReset){
            $user->notify(new PasswordResetRequest($passwordReset->token));
            return redirect(route('admin.resetPassword'))->with('message', 'We have e-mailed your password reset link!');
        }
    }
    
    /**
     * Reset password form
     * It is use to reset password according to resend link to user email. Here user enter his new password and confirm new password for reset his password. 
    */
    public function resetPasswordForm(Request $request)
    {
        
        $pageConfigs = ['bodyCustomClass' => 'forgot-bg', 'isCustomizer' => false];
        return view('pages.user-reset-password', ['pageConfigs' => $pageConfigs]);
    }
    
    /**
     * Reset password
     * Here password reseton basis of enter new password and confirm new password for reset his password. 
    */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'password_confirmation' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/|same:password',
            'token' => 'required|string'
        ],[
            'password_confirmation.required'=>'The confirm password field is required.',
            'password_confirmation.same'=>'The confirm password is not the same password must match same value.',
            'password_confirmation.min'=>'The confirm password must be at least 8 characters.',
            'password_confirmation.regex'=>'The confirm password format is invalid.',
            ]);

        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator); 
        } 

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

        if (!$passwordReset){
            return redirect()->back()->withInput()->withErrors(['error'=>'This password reset token is invalid.']);
        }
            
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user){
            return redirect()->back()->withInput()->withErrors(['error'=>'We can'."'".'t find a user with that e-mail address.']);
        }
            
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));
        return redirect(route('admin.login'))->with('message', 'Your password has been successfully updated');
        
    }
}
