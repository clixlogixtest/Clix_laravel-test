<?php

namespace App\Http\Controllers\API;

use App\Events\ContactMail;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\MobileContent;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function getPage(Request $request)
    {
        $arr=[];
        
        $input=$request->all();
        if(isset($input['slug']))
        {
            $slug=$input['slug'];
            $data=Page::where('slug',$slug)
                ->where('status','active')
                ->select('pages.*',DB::raw('CASE WHEN image IS NOT NULL THEN CONCAT("'.asset('uploads/pages').'","/",image) ELSE "" END as image'))
                ->first();

            if (!empty($data)) 
            {
                $arr['status']=200;
                $arr['message']='Success!';
                $arr['data']=$data;
                
                return response()->json($arr,200);
            }
            else
            {
                $arr['status']=200;
                $arr['message']='No data found!';
                $arr['data']=NULL;

                return response()->json($arr,200);
            }
        }
        else
        {
            $data=Page::where('status','active')
                ->select('pages.*',DB::raw('CASE WHEN image IS NOT NULL THEN CONCAT("'.asset('uploads/pages').'","/",image) ELSE "" END as image'))
                ->get()->toArray();

            if (!empty($data)) 
            {
                $arr['status']=200;
                $arr['message']='Success!';
                $arr['data']=$data;
                
                return response()->json($arr,200);
            }
            else
            {
                $arr['status']=200;
                $arr['message']='No data found!';
                $arr['data']=NULL;

                return response()->json($arr,200);
            }
        }
    }

    public function storeContactUs(Request $request)
    {
        $arr=[];

        $validate=Validator::make($request->all(),[
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email',
            'subject'=>'required',
            'message'=>'required',
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
            elseif(array_key_exists('email',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('email');
                $arr['data']=NULL;
            }
            elseif(array_key_exists('subject',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('subject');
                $arr['data']=NULL;
            }
            elseif(array_key_exists('message',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('message');
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
            $contact=Contact::create($data);
            if($contact)
            {
                $contactData=[
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'subject' => $data['subject'],
                    'msg' => $data['message'],
                ];

                /********Send Mail To Admin******/
                event(new ContactMail($contactData));
                /*********************************/

                $arr['status']=200;
                $arr['message']='Thank you for contacting us, We will revert you very soon!';
                $arr['data']=NULL;

                return response()->json($arr,200);
            }
            else
            {
                $arr['status']=400;
                $arr['message']='Some problems occurred. Please try again!';
                $arr['data']=NULL;

                return response()->json($arr,400);
            }
        }
        catch(\Exception $e)
        {
            $arr['status']=500;
            $arr['message']='Try again!';
            $arr['data']= NULL;

            return response()->json($arr,500);
        }
    }

    public function getFaq(Request $request)
    {
    	$arr=[];

    	$data=Faq::where('status','active')
        	->get()->toArray();

        $mobile_content=MobileContent::where('screen','faq')
            ->where('status','active')
            ->select('mobile_contents.*',DB::raw('CASE WHEN image IS NOT NULL THEN CONCAT("'.asset('uploads/mobile_contents').'","/",image) ELSE "" END as image'))
            ->first();

    	if (!empty($data) || !empty($mobile_content)) 
    	{
    		$arr['status']=200;
    		$arr['message']='Success!';
    		$arr['data']=!empty($data) ? $data : NULL;
    		$arr['mobile_content']=$mobile_content;

            return response()->json($arr,200);
    	}
    	else
    	{
    		$arr['status']=200;
    		$arr['message']='No data found!';
    		$arr['data']=NULL;
    		$arr['mobile_content']=NULL;

            return response()->json($arr,200);
    	}
    }
}
