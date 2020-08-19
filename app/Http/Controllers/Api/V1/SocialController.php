<?php

namespace App\Http\Controllers\Api\V1;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\User; 
use Auth; 
use Validator;

class SocialController extends Controller
{
    public $successStatus = 200;
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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
    * Handle Social login request
    *
    * @return response
    */
   public function socialLogin($social)
   {
       return Socialite::driver($social)->redirect();
   }
   /**
    * Obtain the user information from Social Logged in.
    * @param $social
    * @return Response
    */
   public function handleProviderCallback(Request $request)
   { 
      // Get the value from the form
      
       //$userSocial = Socialite::driver($request->provider)->user();
       //$user = new user();
       //$user = $user::where(['email' => $request->email, 'provider' => $request->provider, 'provider_id' => $request->provider_id])->get();
       $user = User::where([['provider_id', '=', $request->provider_id], ['email', '=', $request->email], ['provider', '=', $request->provider]])->first();
       //$au = Auth::attempt(['email' => $request->email, 'password' => $request->provider_id]);
       //dd($user);
       if($user){ 

       	$date_of_birth = str_replace('/', '-', $request->date_of_birth);
        $date_of_birth = strtotime($date_of_birth);
        $date_of_birth= date('Y-m-d H:i:s', $date_of_birth);
        
        
        $user->provider_id = $request->provider_id;
        $user->provider = $request->provider;
       	$user->role = 'player';
        $user->first_name = $request->first_name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->date_of_birth = $date_of_birth;
        $user->town = $request->town;
        //$user->contact_number = '';
        $user->organisation_id = 1;
        $user->status = $request->status;
        $mobile_access_token = $request->mobile_access_token ? $request->mobile_access_token :'';
        if($mobile_access_token){
           $user->mobile_access_token = $mobile_access_token;
        }
        
        $user->save();
         
              
           $success['message'] =  'SUCCESS'; //print_r($user);
           $success['status'] =  Response::HTTP_CREATED;
           $success['token'] =  $user->createToken('MyApp')->accessToken; 
           $success['data'] =  $user;
           return response()->json(['success' => $success], $this->successStatus); 
           //return redirect()->action('HomeController@index');
       }else{

            $validator = Validator::make($request->all(), [ 
            
            'email' => 'required|email|unique:users,email', 
            'provider' => 'required',           
            'provider_id' => 'required|unique:users',
            'date_of_birth' => 'required|date_format:d/m/Y|before:18 years ago', 
        ],[
          'date_of_birth.required' => 'Please enter Date of Birth.',
          'date_of_birth.before' => 'User must be over 18 to register.',
        ]);
        if($validator->fails()){ 
            return response()->json(['error'=>$validator->errors()], $this->successStatus);    
        }

            $input = $request->all(); 
            $input['password'] = ''; 
            //$input['password'] = bcrypt($input['provider_id']); 
            $user1 = new User; //print_r($user1);
            $date_of_birth = str_replace('/', '-', $request->date_of_birth);
	        $date_of_birth = strtotime($date_of_birth);
	        $date_of_birth= date('Y-m-d H:i:s', $date_of_birth);
	        
	        
	        $user1->provider_id = $request->provider_id;
	        $user1->provider = $request->provider;
	       	$user1->role = 'player';
	        $user1->first_name = $request->first_name;
	        $user1->surname = $request->surname;
	        $user1->email = $request->email;
	        $user1->date_of_birth = $date_of_birth;
	        $user1->town = $request->town;
	        //$user->contact_number = '';
	        $user1->organisation_id = 1;
	        $user1->status = $request->status;
	        $mobile_access_token = $request->mobile_access_token ? $request->mobile_access_token :'';
	        if($mobile_access_token){
	           $user1->mobile_access_token = $mobile_access_token;
	        }
	        
	        $user1->save();
        
            $userss = User::where([['provider_id', '=', $request->provider_id], ['email', '=', $request->email], ['provider', '=', $request->provider]])->first();
            $success['message'] =  'SUCCESS';
            $success['status'] =  Response::HTTP_CREATED;
            $success['token'] =  $userss->createToken('MyApp')->accessToken; 
            $success['data'] =  $userss;
            return response()->json(['success'=>$success], Response::HTTP_CREATED);
       }
   }
}
