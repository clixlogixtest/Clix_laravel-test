<?php

namespace App\Http\Controllers\API;

use App\Events\SendEmailVerificationMail;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMeta;
use App\Traits\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Mail;

class RegisterController extends Controller
{
    use Common;

    /**
     * @OA\Post(
     ** path="/register",
     *   tags={"Register"},
     *   summary="Register",
     *   operationId="register",
     *
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
     *   @OA\Parameter(
     *      name="gender",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="provider_type",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="provider_token",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="device_token",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
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

    public function register(Request $request)
    {
    	$arr=[];
    	
		$typeValidate=Validator::make($request->all(),[
            'provider_type'=>'required',
			'device_token'=>'required',
		]);

		if ($typeValidate->fails()) 
        {
        	$checker=$typeValidate->messages()->get('*');
            if(array_key_exists('provider_type',$checker))
            {
                $arr['status']=422;
                $arr['message']=$typeValidate->errors()->first('provider_type');
                $arr['data']=NULL;
            }
            elseif(array_key_exists('device_token',$checker))
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

        switch ($request->provider_type) 
        {
            /*
            @provider_type=apple
            @provider_type=facebook
            @provider_type=google
            @provider_type=mobile
            @provider_type=normal
            */

            case 'normal':
				$validate=Validator::make($request->all(),[
                    'first_name'=>'required',
                    'last_name'=>'required',
					'email'=>'required|email|unique:users',
                    'date_of_birth'=>'required',
                    'password'=>'required|min:8|regex:/[@$!%*#?&]/',
					'confirm_password'=>'required|same:password',
                    'gender'=>'required',
				], [
                    'password.regex' => 'Password must contain at least 1 special character.'
                ]);

                if($validate->fails())
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
                    elseif(array_key_exists('email',$checker))
                    {
                        $arr['status']=422;
                        $arr['message']=$validate->errors()->first('email');
                        $arr['data']=NULL;
                    }
                    elseif(array_key_exists('date_of_birth',$checker))
                    {
                        $arr['status']=422;
                        $arr['message']=$validate->errors()->first('date_of_birth');
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

            break;

            case $request->provider_type == 'apple' || $request->provider_type == 'facebook' || $request->provider_type == 'google':

				$validate=Validator::make($request->all(),[
                    'email'=>'required|email',
					'provider_token'=>'required|unique:users',
				]);

                if($validate->fails())
                {
                    $checkUser=User::where('provider_type',$request->provider_type)
                        ->where('provider_token',$request->provider_token)
                        ->first();

                    if($checkUser)
                    {
                        $user=Auth::loginUsingId($checkUser->id, true);
                        if($user)
                        {
                            /*************User Meta**************/
                            if($request->has('device_token'))
                            {
                                /*if($this->checkUserMetaDeviceToken($user->id,'device_token',$request->device_token) == 0)
                                {
                                    UserMeta::create([
                                        'user_id' => $user->id,
                                        'meta_key' => 'device_token',
                                        'meta_value' => $request->device_token,
                                    ]);
                                }*/

                                $this->addUserMeta($user->id,'device_token',$request->device_token);
                            }
                            /************************************/
                            
                            $userData=User::where('id',$user->id)->first();

                            if(empty($userData->provider_token))
                            {
                                $userData->provider_token="";
                            }

                            $userData->user_meta=$this->getUserMetaData($userData->id);

                            $arr['status']=200;
                            $arr['message']='Successfully logged in!';
                            $arr['data']['user']=$userData;
                            $arr['data']['token']=$userData->createToken('CompanyFormation')->accessToken;

                            return response()->json($arr,200);
                        }
                    }

                    $checker=$validate->messages()->get('*');
                    if(array_key_exists('email',$checker))
                    {
                        $arr['status']=422;
                        $arr['message']=$validate->errors()->first('email');
                        $arr['data']=NULL;
                    }
                    elseif(array_key_exists('provider_token',$checker))
                    {
                        $arr['status']=422;
                        $arr['message']=$validate->errors()->first('provider_token');
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

            break;
        }

        try
        {
            $data=$request->all();

            /*************Check Email**************/
            if(isset($data['email']) && !empty($data['email']))
            {
                $checkEmail=User::where('email',$data['email'])->first();
                if($checkEmail)
                {
                    $arr['status']=409;
                    $arr['message']='The email has already been taken.';
                    $arr['data']=NULL;

                    return response()->json($arr,409);
                }
            }
            /************************************/

            if(isset($data['password']))
            {
                $data['password']=Hash::make($data['password']);    
            }

            if($data['provider_type'] != 'normal')
            {
                $data['email_verified_at']=Date::now();
            }

            if($data['provider_type'] == 'normal')
            {
                $data['email_verify_token']=Str::random(60);
            }
            
            $user=User::create($data);
            if($user)
            {
                /*************User Meta**************/
                if(isset($data['first_name']))
                {
                    $this->addUserMeta($user->id,'first_name',$data['first_name']);
                }

                if(isset($data['last_name']))
                {
                    $this->addUserMeta($user->id,'last_name',$data['last_name']);
                }

                if(isset($data['date_of_birth']))
                {
                    $this->addUserMeta($user->id,'date_of_birth',$data['date_of_birth']);
                }

                if(isset($data['gender']))
                {
                    $this->addUserMeta($user->id,'gender',$data['gender']);
                }

                if(isset($data['device_token']))
                {
                    $this->addUserMeta($user->id,'device_token',$data['device_token']);
                }
                /************************************/

                $userData=User::where('id',$user->id)->first();

                if(empty($userData->provider_token))
                {
                    $userData->provider_token="";
                }

                $userData->user_meta=$this->getUserMetaData($userData->id);

                /********Send Email Verification Mail******/
                if($userData->provider_type == 'normal')
                {
                    event(new SendEmailVerificationMail($userData));
                }
                /******************************************/

                $arr['status']=200;
                $arr['message']='Successfully registered!';
                $arr['data']['user']=$userData;
                $arr['data']['token']=$userData->createToken('CompanyFormation')->accessToken;

                return response()->json($arr,200);
            }
            else
            {
                $arr['status']=400;
                $arr['message']='Registration failed. Please try again!';
                $arr['data']=NULL;

                return response()->json($arr,400);
            }
        }
        catch(\Exception $e)
        {
            $arr['status']=500;
            $arr['message']="Try again!";
            $arr['data']=NULL;

            return response()->json($arr,500);
        }
    }
}
