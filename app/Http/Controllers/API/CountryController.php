<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    public function getCountries(Request $request)
    {
    	$arr=[];

        if(isset($request->country_id) && !empty($request->country_id))
        {
            $data=Country::where('id',$request->country_id)
	            ->where('status','active')
	            ->first();
        }
        else
        {
        	$data=Country::where('status','active')
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
