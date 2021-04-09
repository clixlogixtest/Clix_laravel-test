<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use JustSteveKing\CompaniesHouseLaravel\Client;

class HomeScreenController extends Controller
{
    public function searchCompanies(Request $request)
    {
    	$arr=[];
        
        $validate=Validator::make($request->all(),[
            'q'=>'required',
            'items_per_page'=>'integer',
            'start_index'=>'integer',
        ]);

        if ($validate->fails()) 
        {
            $checker=$validate->messages()->get('*');
            if(array_key_exists('q',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('q');
                $arr['data']=NULL;
            }
            elseif(array_key_exists('items_per_page',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('items_per_page');
                $arr['data']=NULL;
            }
            elseif(array_key_exists('start_index',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('start_index');
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
            $q=$request->q;

            $items_per_page=null;
            if(isset($request->items_per_page) && !empty($request->items_per_page))
            {
                $items_per_page=$request->items_per_page;
            }

            $start_index=null;
            if(isset($request->start_index) && !empty($request->start_index))
            {
                $start_index=$request->start_index;
            }

        	$api = Client::make();
        	
			// Get a collection of Company\SearchResult inside of a CompanyCollection
			$results = $api->searchCompany($q,$items_per_page,$start_index);
			
			if($results->isEmpty())
			{
				$arr['status']=200;
				$arr['message']=$q . " is available \n If you're happy with this name, click Continue to view the packages.";
				$arr['data']=$q;

				return response()->json($arr,200);
			}
			else
            {
                $arr['status']=404;
                $arr['message']=$q . " is not available \n This company name is too similar to one already registered with Companies House. \n Please search again for a new company name.";
                $arr['data']=$q;

                return response()->json($arr,404);
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
