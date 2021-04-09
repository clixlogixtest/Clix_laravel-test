<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MobileContent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MobileContentController extends Controller
{
    public function getHomeContent(Request $request)
    {
    	$arr=[];

        $screen='home';
        if(isset($request->screen) && !empty($request->screen))
        {
            $screen=$request->screen;
        }

        $data=MobileContent::where('screen',$screen)
            ->where('status','active')
            ->select('mobile_contents.*',DB::raw('CASE WHEN image IS NOT NULL THEN CONCAT("'.asset('uploads/mobile_contents').'","/",image) ELSE "" END as image'))
            ->first();

    	if (!empty($data)) 
    	{
            if($data->content)
            {
                $data->content=json_decode($data->content);
            }

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
