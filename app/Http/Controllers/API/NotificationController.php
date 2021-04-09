<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AddonService;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Package;
use App\Traits\Common;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    use Common;

    public function getNotifications(Request $request)
    {
        $arr=[];
    	$data=[];

    	$user_id=Auth::id();
    	$notifications=Notification::where('user_id',$user_id)
            ->where('status','active')
            ->latest()->get()->toArray();

        foreach ($notifications as $key => $notification) 
        {
            $data[$key]=$notification;
            $data[$key]['created_at']=Carbon::parse($notification['created_at'])->format('Y-m-d H:i:s A');
            $data[$key]['parse_date']=Carbon::parse($notification['created_at'])->diffForHumans();
        }

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

    public function updateNotificationReadStatus(Request $request)
    {
        $arr=[];
        $data=[];
        
        $validate=Validator::make($request->all(),[
            'id'=>'required|integer|exists:notifications,id',
        ]);

        if ($validate->fails()) 
        {
            $checker=$validate->messages()->get('*');
            if(array_key_exists('id',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('id');
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
            $update=Notification::where('id',$request->id)->update(['is_read'=>'yes']);

            if($update)
            {
                $notifications=Notification::where('user_id',$user_id)
                    ->where('status','active')
                    ->latest()->get()->toArray();

                foreach ($notifications as $key => $notification) 
                {
                    $data[$key]=$notification;
                    $data[$key]['created_at']=Carbon::parse($notification['created_at'])->format('Y-m-d H:i:s A');
                    $data[$key]['parse_date']=Carbon::parse($notification['created_at'])->diffForHumans();
                }

                $arr['status']=200;
                $arr['message']='Success!';
                $arr['data']=!empty($data) ? $data : NULL;

                return response()->json($arr,200);
            }
            else
            {
                $arr['status']=400;
                $arr['message']='Something went wrong. Please try again!';
                $arr['data']=NULL;

                return response()->json($arr,400);
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
}
