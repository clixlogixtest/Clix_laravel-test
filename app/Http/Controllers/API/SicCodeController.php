<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SicCodeMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SicCodeController extends Controller
{
    public function getSicCodes(Request $request)
    {
    	$arr=[];

    	if(isset($request->is_default) && $request->is_default != "")
    	{
    		$sic_codes=SicCodeMaster::where('status','active')
    			->where('is_default',$request->is_default)
				->latest()->get()->toArray();
    	}
    	else
    	{
    		$sic_codes=SicCodeMaster::where('status','active')
				->latest()->get()->toArray();
    	}

    	if(!empty($sic_codes))
    	{
    		$arr['status']=200;
            $arr['message']='Success!';
            $arr['data']=$sic_codes;

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
