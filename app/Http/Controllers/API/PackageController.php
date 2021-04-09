<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    public function getPackages(Request $request)
    {
    	$arr=[];
    	
        if(isset($request->package_id) && !empty($request->package_id))
        {
            $data=Package::with(['services_includes:package_id,content'])
                ->where('packages.id',$request->package_id)
                ->where('packages.status','active')
                ->select('packages.*',DB::raw('CASE WHEN packages.image IS NOT NULL THEN CONCAT("'.asset('uploads/packages').'","/",packages.image) ELSE "" END as image'))
                ->orderBy('packages.created_at','DESC')
                ->first();
        }
        else
        {
            $data=Package::with(['services_includes:package_id,content'])
                ->where('packages.status','active')
                ->select('packages.*',DB::raw('CASE WHEN packages.image IS NOT NULL THEN CONCAT("'.asset('uploads/packages').'","/",packages.image) ELSE "" END as image'))
                ->orderBy('packages.created_at','DESC')
                ->get()->toArray();
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
}
