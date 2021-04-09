<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\CompanyDetail;
use App\Models\PersonalDetail;
use App\Models\SecurityQuestion;
use App\Models\SicCode;
use App\Traits\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CompanyDetailController extends Controller
{
	use Common;

    public function getCompanyDetail(Request $request)
    {
    	$arr=[];
        $temp=[];

        try
        {
            $user_id=Auth::id();

            $personal_detail=PersonalDetail::where('user_id',$user_id)->latest()->first();

            $residential_address=Address::where('user_id',$user_id)->where('address_type','residential_address')->latest()->first();
            $residential_address->country_name=!empty(getCountry($residential_address->country_id)) ? getCountry($residential_address->country_id)->en_short_name : '';

            $billing_address=Address::where('user_id',$user_id)->where('address_type','billing_address')->latest()->first();
            $billing_address->country_name=!empty(getCountry($billing_address->country_id)) ? getCountry($billing_address->country_id)->en_short_name : '';

            $registered_office_address=Address::where('user_id',$user_id)->where('address_type','registered_office_address')->latest()->first();
            $registered_office_address->country_name=!empty(getCountry($registered_office_address->country_id)) ? getCountry($registered_office_address->country_id)->en_short_name : '';

            $security_question=SecurityQuestion::where('user_id',$user_id)->latest()->first();

            $sic_code=SicCode::where('user_id',$user_id)->latest()->first();

            $company_detail=CompanyDetail::where('user_id',$user_id)->latest()->first();

            $temp['personal_detail']=$personal_detail;
            $temp['residential_address']=$residential_address;
            $temp['billing_address']=$billing_address;
            $temp['registered_office_address']=$registered_office_address;
            $temp['security_question']=$security_question;
            $temp['sic_code']=$sic_code;
            $temp['company_detail']=$company_detail;
            
            $arr['status']=200;
            $arr['message']='Success!';
            $arr['data']=$temp;

            return response()->json($arr,200);
        }
        catch(\Exception $e)
        {
            $arr['status']=500;
            $arr['message']='Try again!';
            $arr['data']=NULL;

            return response()->json($arr,500);
        }
    }

    public function addCompanyDetail(Request $request)
    {
        $arr=[];
    	$temp=[];

    	$validate=Validator::make($request->all(),[
            'title'=>'required',
            'first_name'=>'required',
            'last_name'=>'required',
            'job_title'=>'required',
            'nationality'=>'required',
            'date_of_birth'=>'required|date_format:Y-m-d|before:today',
            'town_of_birth'=>'required',
            'phone_number'=>'required|digits:10',
            'residential_building_number'=>'required',
            'residential_pincode'=>'required',
            'residential_street'=>'required',
            'residential_city'=>'required',
            'residential_country_id'=>'required|integer|exists:countries,id',
            //'residential_address_line_2'=>'required',
            //'residential_address_type'=>'required|in:residential_address,billing_address,registered_office_address,other_address',
            'use_residential_address'=>'required|in:yes,no',
            'use_existing_address'=>'required|in:yes,no',
            'question'=>'required',
            'answer'=>'required',
            'use_default_sic_code'=>'required|in:yes,no',
            'sic_codes'=>'required',
        ]);

        if ($validate->fails()) 
        {
            $arr['status']=422;
            $arr['message']='Validation failed!';
            $arr['data']=!empty($validate->errors()) ? $validate->errors() : NULL;

            return response()->json($arr,422);
        }

        if($request->has('use_residential_address') && $request->use_residential_address == 'no')
        {
            $validate=Validator::make($request->all(),[
                'billing_building_number'=>'required',
                'billing_pincode'=>'required',
                'billing_street'=>'required',
                'billing_city'=>'required',
                'billing_country_id'=>'required|integer|exists:countries,id',
                //'billing_address_line_2'=>'required',
                //'billing_address_type'=>'required|in:residential_address,billing_address,registered_office_address,other_address',
            ]);

            if ($validate->fails()) 
            {
                $arr['status']=422;
                $arr['message']='Validation failed!';
                $arr['data']=!empty($validate->errors()) ? $validate->errors() : NULL;

                return response()->json($arr,422);
            }
        }

        if($request->has('use_existing_address') && $request->use_existing_address == 'no')
        {
            $validate=Validator::make($request->all(),[
                'registered_office_building_number'=>'required',
                'registered_office_pincode'=>'required',
                'registered_office_street'=>'required',
                'registered_office_city'=>'required',
                'registered_office_country_id'=>'required|integer|exists:countries,id',
                //'registered_office_address_line_2'=>'required',
                //'registered_office_address_type'=>'required|in:residential_address,billing_address,registered_office_address,other_address',
            ]);
        }
        else
        {
            $validate=Validator::make($request->all(),[
                //'address_id'=>'required|integer|exists:addresses,id',
                'existing_address'=>'required|in:residential,billing',
            ]);
        }

        if ($validate->fails()) 
        {
            $arr['status']=422;
            $arr['message']='Validation failed!';
            $arr['data']=!empty($validate->errors()) ? $validate->errors() : NULL;

            return response()->json($arr,422);
        }

        try
        {
            $user_id=Auth::id();
        	$data=$request->all();
        	$data['user_id']=$user_id;
        	
        	/*************Personal Details**************/
        	$personal_data=$request->only([
        		'title', 
        		'first_name',
        		'last_name',
        		'job_title',
        		'nationality',
        		'date_of_birth',
        		'town_of_birth',
        		'phone_number'
        	]);
        	$personal_data['user_id']=$user_id;
        	$personal_detail=PersonalDetail::updateOrCreate(['user_id'=>$user_id],$personal_data);
        	/************************************/

            /*************Residential Address**************/
            $residential_data=[
                'building_number' => $request->residential_building_number, 
                'pincode' => $request->residential_pincode,
                'street' => $request->residential_street,
                'city' => $request->residential_city,
                'country_id' => $request->residential_country_id,
                'address_line_2' => $request->residential_address_line_2,
                'address_type' => 'residential_address',
            ];
            $residential_data['user_id']=$user_id;
            $residential_address=Address::updateOrCreate(['user_id'=>$user_id,'address_type'=>'residential_address'],$residential_data);
            /************************************/

            /*************Billing Address**************/
            if($request->has('use_residential_address') && $request->use_residential_address == 'yes')
            {
                $billing_data=[
                    'building_number' => $request->residential_building_number, 
                    'pincode' => $request->residential_pincode,
                    'street' => $request->residential_street,
                    'city' => $request->residential_city,
                    'country_id' => $request->residential_country_id,
                    'address_line_2' => $request->residential_address_line_2,
                    'address_type' => 'billing_address',
                    'use_residential_address' => $request->use_residential_address,
                ];
            }
            else
            {
                $billing_data=[
                    'building_number' => $request->billing_building_number,
                    'pincode' => $request->billing_pincode,
                    'street' => $request->billing_street,
                    'city' => $request->billing_city,
                    'country_id' => $request->billing_country_id,
                    'address_line_2' => $request->billing_address_line_2,
                    'address_type' => 'billing_address',
                    'use_residential_address' => $request->use_residential_address,
                ];
            }
            $billing_data['user_id']=$user_id;
            $billing_address=Address::updateOrCreate(['user_id'=>$user_id,'address_type'=>'billing_address'],$billing_data);
            /************************************/

        	/*************Registered Office Address**************/
            if($request->has('use_existing_address') && $request->use_existing_address == 'yes')
            {
                if($request->has('existing_address') && $request->existing_address == 'residential')
                {
                    $registered_office_data=$residential_data;
                    $registered_office_data['address_type']='registered_office_address';
                    $registered_office_data['use_existing_address']=$request->use_existing_address;
                    $registered_office_data['existing_address']=$request->existing_address;
                }
                elseif($request->has('existing_address') && $request->existing_address == 'billing')
                {
                    $registered_office_data=$residential_data;
                    $registered_office_data['address_type']='registered_office_address';
                    $registered_office_data['use_existing_address']=$request->use_existing_address;
                    $registered_office_data['existing_address']=$request->existing_address;
                }
            }
        	else
            {
                $registered_office_data=[
                    'building_number' => $request->registered_office_building_number,
                    'pincode' => $request->registered_office_pincode,
                    'street' => $request->registered_office_street,
                    'city' => $request->registered_office_city,
                    'country_id' => $request->registered_office_country_id,
                    'address_line_2' => $request->registered_office_address_line_2,
                    'address_type' => 'registered_office_address',
                    'use_existing_address' => $request->use_existing_address,
                    'existing_address' => NULL,
                ];
            }
            $registered_office_data['user_id']=$user_id;
            $registered_office_address=Address::updateOrCreate(['user_id'=>$user_id,'address_type'=>'registered_office_address'],$registered_office_data);
        	/************************************/

        	/*************Security Questions**************/
        	$security_data=$request->only([
        		'question', 
        		'answer'
        	]);
        	$security_data['user_id']=$user_id;
        	$security_question=SecurityQuestion::updateOrCreate(['user_id'=>$user_id],$security_data);
        	/************************************/

            /*************SIC Codes**************/
            $sic_code_data=[
                'code' => $request->sic_codes,
                'use_default_sic_code' => $request->use_default_sic_code, 
            ];
            $sic_code_data['user_id']=$user_id;
            $sic_code=SicCode::updateOrCreate(['user_id'=>$user_id],$sic_code_data);
            /************************************/

            /*************Company Details**************/
            $company_data=[
                'personal_detail_id'=>$personal_detail->id,
                'residential_address_id'=>$residential_address->id,
                'billing_address_id'=>$billing_address->id,
                'registered_office_address_id'=>$registered_office_address->id,
                'security_question_id'=>$security_question->id,
            ];
            $company_data['user_id']=$user_id;
            $company_detail=CompanyDetail::updateOrCreate(['user_id'=>$user_id],$company_data);
            /************************************/

            $residential_address->country_name=!empty(getCountry($residential_address->country_id)) ? getCountry($residential_address->country_id)->en_short_name : '';
            $billing_address->country_name=!empty(getCountry($billing_address->country_id)) ? getCountry($billing_address->country_id)->en_short_name : '';
            $registered_office_address->country_name=!empty(getCountry($registered_office_address->country_id)) ? getCountry($registered_office_address->country_id)->en_short_name : '';

            $temp['personal_detail']=$personal_detail;
            $temp['residential_address']=$residential_address;
            $temp['billing_address']=$billing_address;
            $temp['registered_office_address']=$registered_office_address;
            $temp['security_question']=$security_question;
            $temp['sic_code']=$sic_code;
            $temp['company_detail']=$company_detail;

        	$arr['status']=200;
            $arr['message']='Company details successfully added!';
            $arr['data']=$temp;

            return response()->json($arr,200);
        	
        }
        catch(\Exception $e)
        {
            $arr['status']=500;
            $arr['message']='Try again!';
            $arr['data']=NULL;

            return response()->json($arr,500);
        }
    }

    public function updateBillingAddress(Request $request)
    {
        $arr=[];
        $temp=[];

        $validate=Validator::make($request->all(),[
            'billing_address_id'=>'required|integer|exists:addresses,id',
            'billing_building_number'=>'required',
            'billing_pincode'=>'required',
            'billing_street'=>'required',
            'billing_city'=>'required',
            'billing_country_id'=>'required|integer|exists:countries,id',
        ]);

        if ($validate->fails()) 
        {
            $arr['status']=422;
            $arr['message']='Validation failed!';
            $arr['data']=!empty($validate->errors()) ? $validate->errors() : NULL;

            return response()->json($arr,422);
        }

        try
        {
            $data=$request->all();
            $data['user_id']=Auth::id();

            $check_billing_address=Address::where('id',$data['billing_address_id'])
                ->where('user_id',$data['user_id'])
                ->where('address_type','billing_address')
                ->first();

            if(!$check_billing_address)
            {
                $arr['status']=404;
                $arr['message']='Billing address not found!';
                $arr['data']=NULL;

                return response()->json($arr,404);
            }

            $billing_data=[
                'building_number' => $request->billing_building_number,
                'pincode' => $request->billing_pincode,
                'street' => $request->billing_street,
                'city' => $request->billing_city,
                'country_id' => $request->billing_country_id,
                'address_line_2' => $request->billing_address_line_2,
            ];

            $update_billing_address=Address::where('id',$check_billing_address->id)->update($billing_data);

            $billing_address=Address::where('user_id',$data['user_id'])
                ->where('address_type','billing_address')
                ->latest()->first();
            $billing_address->country_name=!empty(getCountry($billing_address->country_id)) ? getCountry($billing_address->country_id)->en_short_name : '';

            $temp['billing_address']=$billing_address;

            $arr['status']=200;
            $arr['message']='Billing address successfully updated!';
            $arr['data']=$temp;

            return response()->json($arr,200);
        }
        catch(\Exception $e)
        {
            $arr['status']=500;
            $arr['message']='Try again!';
            $arr['data']=NULL;

            return response()->json($arr,500);
        }
    }
}
