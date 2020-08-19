<?php

namespace App\Http\Controllers\Api\V1;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller; 
use App\User; 
use App\Model\Organisations;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Carbon\Carbon;
use App\Notifications\NewUserPasswordSendSuccessfully;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\PasswordReset;

class AuthController extends Controller
{
	public $successStatus = 200;

    
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function signin(Request $request)
    {
        $validator = Validator::make([
            'email' => request('email'),
            'password' => request('password')
            
        ],
        [
            'email' => 'required|email',
            'password' => 'required|min:8|max:20'
            
        ]);

        if($validator->fails()){ 
            $resp = [
                'error' => $validator->errors(),
                'status'  => intval(Response::HTTP_NOT_FOUND)
            ];
            return response()->json($resp, Response::HTTP_OK);    
        }

        if ( Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'player']) ) { 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            $success['name'] =  $user->first_name." ".$user->surname;
            $success['email'] =  $user->email;
            $success['organisation_id'] =  $user->organisation_id;

            $mobile_access_token = $request->mobile_access_token ? $request->mobile_access_token : '';
            if($mobile_access_token){
               $userUpdate = User::where('id', '=', Auth::user()->id)->first(); 
               $userUpdate->mobile_access_token = $mobile_access_token;
               $userUpdate->update();
            }

            
            $resp = [
                'message' => 'SUCCESS',
                'status'  => intval($this-> successStatus),
                'data' => $success
            ];
            //print_r($resp);
            //return response()->json(['success' => $success])->setStatusCode(Response::HTTP_ACCEPTED);
            return response()->json($resp, $this-> successStatus); 
        }else{
            $resp = [
                'error' => 'Unauthorised',
                'status'  => intval(401)
            ];
            return response()->json($resp, $this-> successStatus); 
        }
        
    }

    public function checkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email', 
            

        ]);

        if($validator->fails()){ 
            $resp = [
                'error' => $validator->errors(),
                'status'  => intval(Response::HTTP_NOT_FOUND)
            ];
            return response()->json($resp, Response::HTTP_OK);    
        }

        $input = $request->all();

        

        $email= $input['email'];
        

        $user = User::where('email', '=', $email)->get();
        $user = json_encode($user);
        $user = json_decode($user, true);
        //print_r($user);

        if($user){

            $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_OK),
                'data' => array("email" => $email, "message" => "Email Id is exist in database.")
            ];
            return response()->json($resp, Response::HTTP_OK); 

        }

        $resp = [
            'message' => 'SUCCESS',
            'status'  => intval(Response::HTTP_NOT_FOUND),
            'data' => array("email" => $email, "message" => "Email Id is not exist in database.")
        ];
        return response()->json($resp, Response::HTTP_OK);

    }

    
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'first_name' => 'required', 
            'surname' => 'required', 
            'email' => 'required|email|unique:users', 
            'date_of_birth' => 'required|date_format:d/m/Y|before:18 years ago', 
            'town' => 'required',
            

        ],[
          'role.required' => 'The roles field is required.',
          'date_of_birth.required' => 'Please enter Date of Birth.',
          'date_of_birth.before' => 'User must be 18 or over to register.',
          
        ]);

        if($validator->fails()){ 
            $resp = [
                'error' => $validator->errors(),
                'status'  => intval(Response::HTTP_NOT_FOUND)
            ];
            return response()->json($resp, Response::HTTP_OK);    
        }

        $header = $request->header('Organisation-Api-Key');  
        if(!$header){

            $resp = [
                'error' => 'Header Organisation-Api-Key is require!',
                'status'  => intval(Response::HTTP_NOT_FOUND)
            ];
            return response()->json($resp, Response::HTTP_OK);    
            
        }

        $org = Organisations::where('api_key', $header)->get();

        $input = $request->all();

        $date_of_birth = str_replace('/', '-', $input['date_of_birth']);
        $date_of_birth = strtotime($date_of_birth);
        $date_of_birth= date('Y-m-d H:i:s', $date_of_birth);
        $input['date_of_birth'] = $date_of_birth;

        $email= $input['email'];
        $pass= $this->randomPassword(12);

        $user = new User;
        $user->role = 'player';
        $user->first_name = $input['first_name'];
        $user->surname = $input['surname'];
        $user->email = $input['email'];
        $user->date_of_birth = $date_of_birth;
        $user->town = $input['town'];
        //$user->contact_number = '';
        $user->organisation_id = $org['0']->organisation_id;
        $user->status = 1;
        $mobile_access_token =@$input['mobile_access_token']?$input['mobile_access_token']:'';
        if($mobile_access_token){
           $user->mobile_access_token = @$input['mobile_access_token'];
        }
        
        $user->password = bcrypt($pass);
        $user->save();
        $user->notify(new NewUserPasswordSendSuccessfully($pass, $email));
        
        $success['token'] =  $user->createToken('MyApp')->accessToken; 
        $success['name'] =  $user->first_name;
        $resp = [
            'message' => 'SUCCESS',
            'status'  => intval(Response::HTTP_CREATED),
            'data' => $success
        ];
        return response()->json($resp, Response::HTTP_CREATED); 
        
    }

    public function randomPassword($len = 8) {

        //enforce min length 8
        if($len < 8)
            $len = 8;

        //define character libraries - remove ambiguous characters like iIl|1 0oO
        $sets = array();
        $sets[] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $sets[] = 'abcdefghijklmnopqrstuvwxyz';
        $sets[] = '0123456789';
        $sets[]  = '~!@#$%^&*(){}[],./?';

        $password = '';
        
        //append a character from each set - gets first 4 characters
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
        }

        //use all characters to fill up to $len
        while(strlen($password) < $len) {
            //get a random set
            $randomSet = $sets[array_rand($sets)];
            
            //add a random char from the random set
            $password .= $randomSet[array_rand(str_split($randomSet))]; 
        }
        
        //shuffle the password string before returning!
        return str_shuffle($password);
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
            $resp = [
                'error' => $validator->errors(),
                'status'  => intval(Response::HTTP_NOT_FOUND)
            ];
            return response()->json($resp, Response::HTTP_OK);    
        } 

        $header = $request->header('Organisation-Api-Key');  
        if(!$header){

            $resp = [
                'error' => 'Header Organisation-Api-Key is require!',
                'status'  => intval(Response::HTTP_NOT_FOUND)
            ];
            return response()->json($resp, Response::HTTP_OK);    
            
        }

        $org = Organisations::where('api_key', $header)->get();

        $user = User::where([['email', '=', $request->email], ['organisation_id', '=', $org['0']->organisation_id]])->first();
        if(!$user){
            $resp = [
                'error' => 'We cant find a user with that e-mail address!',
                'status'  => intval(Response::HTTP_NOT_FOUND)
            ];
            return response()->json($resp, Response::HTTP_OK);
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
            //return redirect(route('admin.resetPassword'))->with('message', 'We have e-mailed your password reset link!');

            $resp = [
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_CREATED),
                'data' => 'We have e-mailed your password reset link!'
            ];
            return response()->json($resp, Response::HTTP_CREATED);
        }
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->first();
        if (!$passwordReset){
            $errorData = array('error' =>array("email" => "This password reset token is invalid."),
                              'status'  => intval(Response::HTTP_NOT_FOUND));
            return response()->json($errorData, Response::HTTP_OK);
        }
            
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            $errorData = array('error' =>array("email" => "This password reset token is invalid."),
                              'status'  => intval(Response::HTTP_NOT_FOUND));
            return response()->json($errorData, Response::HTTP_OK);
        }
        $DataColl = array("email"=>$passwordReset->email,"token"=>$passwordReset->token);
        return redirect()->route('admin.resetPasswordForm', $DataColl);
        
    }
}