<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AddonService;
use App\Models\Cart;
use App\Models\MobileContent;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddonServiceController extends Controller
{
    public function getAddonServices(Request $request)
    {
        $arr=[];
    	$data=[];
        $addon_service_id_arr=[];

        $input=$request->all();
        $user_id=Auth::id();

        if(isset($input['package_id']) && isset($input['company_name']))
        {
            $cart_addon_service_id=Cart::where('user_id',$user_id)
                ->where('package_id',$input['package_id'])
                ->where('company_name',$input['company_name'])
                ->value('addon_service_id');

            if($cart_addon_service_id != "")
            {
                $addon_service_id_arr=explode(',', $cart_addon_service_id);
            }
        }
    	
    	$addon_services=AddonService::where('status','active')
    		->select('addon_services.*',DB::raw('CASE WHEN image IS NOT NULL THEN CONCAT("'.asset('uploads/addon_services').'","/",image) ELSE "" END as image'))
    		->orderBy('created_at','DESC')
    		->get()->toArray();

        foreach ($addon_services as $key => $addon_service) 
        {
            $is_addon_service_already_added=false;
            if(in_array($addon_service['id'], $addon_service_id_arr))
            {
                $is_addon_service_already_added=true;
            }

            $data[]=$addon_service;
            $data[$key]['is_addon_service_already_added']=$is_addon_service_already_added;
        }

        $mobile_content=MobileContent::where('screen','addon-services')
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
