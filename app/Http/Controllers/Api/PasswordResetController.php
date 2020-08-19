<?php

namespace App\Http\Controllers\API;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\User;
use App\PasswordReset;
use Validator;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
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
        /*$request->validate([
            'email' => 'required|email'
        ]);*/
        $user = User::where('email', $request->email)->first();
        if (!$user){
            $errorData = array('error' =>array("email" => "We can't find a user with this email-id."),
                              'status'  => intval(Response::HTTP_NOT_FOUND));
            return response()->json($errorData,
                
             Response::HTTP_OK);
        }
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => str_random(60)
             ]
        ); 

         //dd($passwordReset->token);

        if ($user && $passwordReset)
            //dd($passwordReset->token);
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );
        return response()->json([
                'message' => 'We have e-mailed your password reset link!',
                'status'  => intval(Response::HTTP_OK),
            ], Response::HTTP_OK);
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
        return redirect(route('admin.resetPasswordForm'))->with('data', $DataColl);
        return response()->json([
                'message' => 'SUCCESS',
                'status'  => intval('200'),
                'data' => $passwordReset
            ], 200);
    }
     /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8|max:20',
            'password_confirmation' => 'required|min:8|max:20|same:password',
            'token' => 'required|string'
        ],[
            'password_confirmation.required'=>'The confirm password field is required.',
            'password_confirmation.same'=>'The confirm password is not the same password must match same value.',
            'password_confirmation.min'=>'The confirm password must be at least 8 characters.',
            'password_confirmation.max'=>'The confirm password length must be less than or equal to 20 characters.',
            ]);
        if($validator->fails()){ 
            $resp = [
                'error' => $validator->errors(),
                'status'  => intval(Response::HTTP_NOT_FOUND)
            ];
            return response()->json($resp, Response::HTTP_OK);    
        }

        /*$request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|max:20',
            'password_confirmation' => 'required|min:8|max:20|same:password',
            'token' => 'required|string'
        ]);*/
        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();
        if (!$passwordReset)
            $errorData = array('error' =>array("token" => "This password reset token is invalid."),
                              'status'  => intval(Response::HTTP_NOT_FOUND));
            return response()->json($errorData, Response::HTTP_OK);
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
            $errorData = array('error' =>array("email" => 'We can'."'".'t find a user with that e-mail address.'),
                              'status'  => intval(Response::HTTP_NOT_FOUND));
            return response()->json($errorData, Response::HTTP_OK);
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));
        return response()->json([
                'message' => 'SUCCESS',
                'status'  => intval(Response::HTTP_OK),
                'userData' => $user
            ], Response::HTTP_OK);
    }
}
