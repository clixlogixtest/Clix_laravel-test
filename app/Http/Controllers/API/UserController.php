<?php

namespace App\Http\Controllers\API;

use App\Events\SendEmailVerificationMail;
use App\Events\SendForgotPasswordMail;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMeta;
use App\Traits\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Mail;

class UserController extends Controller
{
    use Common;

    /**
     * @OA\Get(
     *      path="/profile",
     *      tags={"Profile"},
     *      operationId="getProfile",
     * security={
     *  {"passport": {}},
     *   },
     *      summary="get Profile",
     *      description="",
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function getProfile(Request $request)
    {
        $arr=[];

        try
        {
            $data=User::where('id',Auth::id())
                ->first();
            
            if($data)
            {
                if(empty($data->provider_token))
                {
                    $data->provider_token="";
                }

                $data->user_meta=$this->getUserMetaData($data->id);

                $arr['status']=200;
                $arr['message']='Success!';
                $arr['data']=$data;

                return response()->json($arr,200);
            }
            else
            {
                $arr['status']=200;
                $arr['message']='User not found!';
                $arr['data']=NULL;

                return response()->json($arr,200);
            }
        }
        catch(\Exception $e)
        {
            $arr['status']=500;
            $arr['message']='Try again!';
            $arr['data']=NULL;

            return response()->json($arr,500);
        }
    }

    /**
     * @OA\Post(
     *      path="/profile",
     *      tags={"Profile"},
     *      operationId="updateProfile",
     * security={
     *  {"passport": {}},
     *   },
     *      summary="update Profile",
     *      description="",
     *  @OA\Parameter(
     *      name="first_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="last_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="job_title",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="nationality",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="date_of_birth",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="town_of_birth",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="gender",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function updateProfile(Request $request)
    {
        $arr=[];
        
        $validate=Validator::make($request->all(),[
            //'first_name'=>'required',
            //'last_name'=>'required',
            //'job_title'=>'required',
            //'nationality'=>'required',
            'date_of_birth'=>'required',
            //'town_of_birth'=>'required',
            'gender'=>'required',
        ]);

        if ($validate->fails()) 
        {
            $checker=$validate->messages()->get('*');
            if(array_key_exists('first_name',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('first_name');
                $arr['data']=NULL;
            }
            elseif(array_key_exists('last_name',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('last_name');
                $arr['data']=NULL;
            }
            elseif(array_key_exists('date_of_birth',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('date_of_birth');
                $arr['data']=NULL;
            }
            elseif(array_key_exists('gender',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('gender');
                $arr['data']=NULL;
            }
            else
            {
                $arr['status']=422;
                $arr['message']='Validation failed!';
                $arr['data']=NULL;
            }

            return response()->json($arr,422);
        }

        try
        {
            $data=$request->all();
            $user=User::where('id',Auth::id())->first();
            if($user)
            {
                if(empty($user->email))
                {
                    if(isset($data['email']))
                    {
                        $checkUser=User::where('id','!=',$user->id)->where('email',$data['email'])->first();
                        if($checkUser)
                        {
                            $arr['status']=409;
                            $arr['message']='The email has already been taken.';
                            $arr['data']=NULL;

                            return response()->json($arr,409);
                        }
                        else
                        {
                            User::where('id',$user->id)->update(['email'=>$data['email']]);
                        }
                    }
                }

                /*************User Meta**************/
                if(isset($data['first_name']))
                {
                    $this->addUserMeta($user->id,'first_name',$data['first_name']);
                }
                else
                {
                    $this->addUserMeta($user->id,'first_name','');
                }

                if(isset($data['last_name']))
                {
                    $this->addUserMeta($user->id,'last_name',$data['last_name']);
                }
                else
                {
                    $this->addUserMeta($user->id,'last_name','');
                }

                if(isset($data['job_title']))
                {
                    $this->addUserMeta($user->id,'job_title',$data['job_title']);
                }
                else
                {
                    $this->addUserMeta($user->id,'job_title','');
                }

                if(isset($data['nationality']))
                {
                    $this->addUserMeta($user->id,'nationality',$data['nationality']);
                }
                else
                {
                    $this->addUserMeta($user->id,'nationality','');
                }

                if(isset($data['date_of_birth']))
                {
                    $this->addUserMeta($user->id,'date_of_birth',$data['date_of_birth']);
                }
                else
                {
                    $this->addUserMeta($user->id,'date_of_birth','');
                }

                if(isset($data['town_of_birth']))
                {
                    $this->addUserMeta($user->id,'town_of_birth',$data['town_of_birth']);
                }
                else
                {
                    $this->addUserMeta($user->id,'town_of_birth','');
                }

                if(isset($data['gender']))
                {
                    $this->addUserMeta($user->id,'gender',$data['gender']);
                }
                else
                {
                    $this->addUserMeta($user->id,'gender','');
                }
                /************************************/
                
                $userData=User::where('id',$user->id)->first();

                if(empty($userData->provider_token))
                {
                    $userData->provider_token="";
                }

                $userData->user_meta=$this->getUserMetaData($userData->id);
                
                $arr['status']=200;
                $arr['message']='Success!';
                $arr['data']=$userData;

                return response()->json($arr,200);
            }
            else
            {
                $arr['status']=200;
                $arr['message']='User not found!';
                $arr['data']=NULL;

                return response()->json($arr,200);
            }
        }
        catch(\Exception $e)
        {
            $arr['status']=500;
            $arr['message']='Try Again!';
            $arr['data']= NULL;

            return response()->json($arr,500);
        }
    }

    /**
     * @OA\Post(
     ** path="/changePassword",
     *   tags={"Change Password"},
     *   summary="Change Password",
     *   operationId="changePassword",
     * security={
     *  {"passport": {}},
     *   },
     *
     *  @OA\Parameter(
     *      name="old_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="new_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="confirm_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
    **/
    public function changePassword(Request $request)
    {
        $arr=[];

        $validate=Validator::make($request->all(),[
            'old_password'=>'required',
            'new_password'=>'required|min:8|regex:/[@$!%*#?&]/',
            'confirm_password'=>'required|same:new_password',
        ], [
            'new_password.regex' => 'Password must contain at least 1 special character.'
        ]);

        if ($validate->fails()) 
        {
            $checker=$validate->messages()->get('*');
            if(array_key_exists('old_password',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('old_password');
                $arr['data']=NULL;
            }
            elseif(array_key_exists('new_password',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('new_password');
                $arr['data']=NULL;
            }
            elseif(array_key_exists('confirm_password',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('confirm_password');
                $arr['data']=NULL;
            }
            else
            {
                $arr['status']=422;
                $arr['message']='Validation failed!';
                $arr['data']=NULL;
            }

            return response()->json($arr,422);
        }

        try
        {
            $userData=User::where('id',Auth::id())
                ->first();

            if($userData)
            {
                if(!Hash::check($request->old_password, $userData->password))
                {
                    $arr['status']=422;
                    $arr['message']='Invalid old password!';
                    $arr['data']=NULL;

                    return response()->json($arr,422);
                }

                $update=User::where('id',$userData->id)
                    ->update(['password'=>Hash::make($request->new_password)]);

                if($update)
                {
                    $arr['status']=200;
                    $arr['message']='Password successfully changed!';
                    $arr['data']=NULL;

                    return response()->json($arr,200);
                }
                else
                {
                    $arr['status']=400;
                    $arr['message']='Password not changed. Please try again!';
                    $arr['data']=NULL;

                    return response()->json($arr,400);
                }
            }
            else
            {
                $arr['status']=200;
                $arr['message']='User not found!';
                $arr['data']=NULL;

                return response()->json($arr,200);
            }
        }
        catch(\Exception $e)
        {
            $arr['status']=500;
            $arr['message']='Try Again!';
            $arr['data']=NULL;

            return response()->json($arr,500);
        }
    }

    /**
     * @OA\Post(
     *      path="/forgotPassword",
     *      tags={"Forgot Password"},
     *      operationId="forgotPassword",
     *      summary="forgot Password",
     *      description="",
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function forgotPassword(Request $request)
    {
    	$arr=[];

        $validate=Validator::make($request->all(),[
            'email'=>'required',
        ]);

        if ($validate->fails()) 
        {
            $checker=$validate->messages()->get('*');
            if(array_key_exists('email',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('email');
                $arr['data']=NULL;
            }
            else
            {
                $arr['status']=422;
                $arr['message']='Validation failed!';
                $arr['data']=NULL;
            }

            return response()->json($arr,422);
        }

        try
        {
            $checkUser=User::where('email',$request->email)
                ->first();

            if($checkUser)
            {
                $otp=rand(100000,999999);

                User::where('id',$checkUser->id)
                    ->update(['otp'=>$otp]);

                $userData=User::where('id',$checkUser->id)
                    ->first();

                if(empty($userData->provider_token))
                {
                    $userData->provider_token="";
                }

                $userData->user_meta=$this->getUserMetaData($userData->id);

                /********Send Email Verification Mail******/
                event(new SendForgotPasswordMail($userData));
                /******************************************/

                $arr['status']=200;
                $arr['message']='OTP Sent on your E-mail';
                $arr['data']=NULL;
            }
            else
            {
                $arr['status']=200;
                $arr['message']='OTP Sent on your E-mail';
                $arr['data']=NULL;
            }

            return response()->json($arr,200);
        }
        catch(\Exception $e)
        {
            $arr['status']=500;
            $arr['message']='Try Again!';
            $arr['data']=NULL;

            return response()->json($arr,500);
        }
    }

    /**
     * @OA\Post(
     *      path="/verifyOtp",
     *      tags={"Verify OTP"},
     *      operationId="verifyOtp",
     *      summary="Verify OTP",
     *      description="",
     *   @OA\Parameter(
     *      name="otp",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function verifyOtp(Request $request)
    {
        $arr=[];

        $validate=Validator::make($request->all(),[
            'email'=>'required',
            'otp'=>'required',
        ]);

        if ($validate->fails()) 
        {
            $checker=$validate->messages()->get('*');
            if(array_key_exists('email',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('email');
                $arr['data']=NULL;
            }
            elseif(array_key_exists('otp',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('otp');
                $arr['data']=NULL;
            }
            else
            {
                $arr['status']=422;
                $arr['message']='Validation failed!';
                $arr['data']=NULL;
            }

            return response()->json($arr,422);
        }

        try
        {
            $userData=User::where('email',$request->email)
                ->first();

            if($userData)
            {
                if($userData->otp != $request->otp)
                {
                    $arr['status']=422;
                    $arr['message']='OTP does not match!';
                    $arr['data']=NULL;

                    return response()->json($arr,422);
                }

                $arr['status']=200;
                $arr['message']='OTP successfully matched!';
                $arr['data']=NULL;

                return response()->json($arr,200);
            }
            else
            {
                $arr['status']=200;
                $arr['message']='User not found!';
                $arr['data']=NULL;

                return response()->json($arr,200);
            }
        }
        catch(\Exception $e)
        {
            $arr['status']=500;
            $arr['message']='Try Again!';
            $arr['data']=NULL;

            return response()->json($arr,500);
        }
    }

    /**
     * @OA\Post(
     ** path="/resetPassword",
     *   tags={"Reset Password"},
     *   summary="Reset Password",
     *   operationId="resetPassword",
     *
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="confirm_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
    **/
    public function resetPassword(Request $request)
    {
        $arr=[];

        $validate=Validator::make($request->all(),[
            'email'=>'required',
            'password'=>'required|string|min:8|regex:/[@$!%*#?&]/',
            'confirm_password'=>'required|same:password',
        ], [
            'password.regex' => 'Password must contain at least 1 special character.'
        ]);

        if ($validate->fails()) 
        {
            $checker=$validate->messages()->get('*');
            if(array_key_exists('email',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('email');
                $arr['data']=NULL;
            }
            elseif(array_key_exists('password',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('password');
                $arr['data']=NULL;
            }
            elseif(array_key_exists('confirm_password',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('confirm_password');
                $arr['data']=NULL;
            }
            else
            {
                $arr['status']=422;
                $arr['message']='Validation failed!';
                $arr['data']=NULL;
            }

            return response()->json($arr,422);
        }

        try
        {
            $userData=User::where('email',$request->email)
                ->first();

            if($userData)
            {
                $update=User::where('id',$userData->id)
                    ->update(['password'=>Hash::make($request->password),'otp'=>NULL]);

                if($update)
                {
                    $arr['status']=200;
                    $arr['message']='Password successfully reset!';
                    $arr['data']=NULL;

                    return response()->json($arr,200);
                }
                else
                {
                    $arr['status']=400;
                    $arr['message']='Password not reset. Please try again!';
                    $arr['data']=NULL;

                    return response()->json($arr,400);
                }
            }
            else
            {
                $arr['status']=200;
                $arr['message']='User not found!';
                $arr['data']=NULL;

                return response()->json($arr,200);
            }
        }
        catch(\Exception $e)
        {
            $arr['status']=500;
            $arr['message']='Try Again!';
            $arr['data']=NULL;

            return response()->json($arr,500);
        }
    }

    /**
     * @OA\Get(
     *      path="/verifyEmail",
     *      tags={"verify Email"},
     *      operationId="verifyEmail",
     * security={
     *  {"passport": {}},
     *   },
     *      summary="verify Email",
     *      description="",
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function verifyEmail(Request $request)
    {
        $arr=[];

        try
        {
            $user_id=Auth::id();
            $checkUser=User::where('id',$user_id)->first();
            if(!$checkUser)
            {
                $arr['status']=200;
                $arr['message']='User not found!';
                $arr['data']=NULL;

                return response()->json($arr,200);
            }

            if(empty($checkUser->email_verified_at) && $checkUser->provider_type == 'normal')
            {
                $email_verify_token=Str::random(60);
                $user=User::where('id',$user_id)->update(['email_verify_token' => $email_verify_token]);

                $userData=User::where('id',$user_id)->first();
                if(empty($userData->provider_token))
                {
                    $userData->provider_token="";
                }
                $userData->user_meta=$this->getUserMetaData($userData->id);

                /********Send Email Verification Mail******/
                event(new SendEmailVerificationMail($userData));
                /******************************************/

                $arr['status']=200;
                $arr['message']='Email verification link sent on your email id!';
                $arr['data']=NULL;

                return response()->json($arr,200);
            }
            else
            {
                $arr['status']=400;
                $arr['message']='Email already verified!';
                $arr['data']=NULL;

                return response()->json($arr,400);
            }
        }
        catch(\Exception $e)
        {
            $arr['status']=500;
            $arr['message']='Try Again!';
            $arr['data']=NULL;

            return response()->json($arr,500);
        }
    }

    /**
     * @OA\Post(
     *      path="/updateDeviceToken",
     *      tags={"update Device Token"},
     *      operationId="updateDeviceToken",
     * security={
     *  {"passport": {}},
     *   },
     *      summary="update Device Token",
     *      description="",
     *
     *   @OA\Parameter(
     *      name="device_token",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function updateDeviceToken(Request $request)
    {
        $arr=[];
        
        $validate=Validator::make($request->all(),[
            'device_token'=>'required',
        ]);

        if ($validate->fails()) 
        {
            $checker=$validate->messages()->get('*');
            if(array_key_exists('device_token',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('device_token');
                $arr['data']=NULL;
            }
            else
            {
                $arr['status']=422;
                $arr['message']='Validation failed!';
                $arr['data']=NULL;
            }

            return response()->json($arr,422);
        }

        try
        {
            $user_id=Auth::id();
            $checkUser=User::where('id',$user_id)->first();
            if(!$checkUser)
            {
                $arr['status']=200;
                $arr['message']='User not found!';
                $arr['data']=NULL;

                return response()->json($arr,200);
            }

            if($request->has('device_token'))
            {
                $this->addUserMeta($user_id,'device_token',$request->device_token);
            }

            $userData=User::where('id',$user_id)->first();
            if(empty($userData->provider_token))
            {
                $userData->provider_token="";
            }
            $userData->user_meta=$this->getUserMetaData($userData->id);
            
            $arr['status']=200;
            $arr['message']='Device Token successfully updated!';
            $arr['data']=$userData;

            return response()->json($arr,200);
        }
        catch(\Exception $e)
        {
            $arr['status']=500;
            $arr['message']='Try Again!';
            $arr['data']=NULL;

            return response()->json($arr,500);
        }
    }

    /**
     * @OA\Post(
     *      path="/logout",
     *      tags={"Logout"},
     *      operationId="logout",
     * security={
     *  {"passport": {}},
     *   },
     *      summary="Logout",
     *      description="",
     *
     *   @OA\Parameter(
     *      name="device_token",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function logout(Request $request)
    {
        $arr=[];
        
        $typeValidate=Validator::make($request->all(),[
            'device_token'=>'required',
        ]);

        if ($typeValidate->fails()) 
        {
            $checker=$typeValidate->messages()->get('*');
            if(array_key_exists('device_token',$checker))
            {
                $arr['status']=422;
                $arr['message']=$typeValidate->errors()->first('device_token');
                $arr['data']=NULL;
            }
            else
            {
                $arr['status']=422;
                $arr['message']='Validation failed!';
                $arr['data']=NULL;
            }

            return response()->json($arr,422);
        }

    	if(Auth::check())
        {
            $user=Auth::user();
            /********Delete User Meta Device Token*******/
            //$this->deleteUserMetaDeviceToken($user->id,'device_token',$request->device_token);
            /********************************************/
            $token=$user->token();
            $token->revoke();

            $arr['status']=200;
            $arr['message']='Successfully logout!';
            $arr['data']=NULL; 

            return response()->json($arr,200);
        }
        else
        {
            $arr['status']=401;
            $arr['message']='Try Again!';
            $arr['data']=NULL; 

            return response()->json($arr,401);
        }
    }
}
