<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AddonService;
use App\Models\Cart;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use JustSteveKing\CompaniesHouseLaravel\Client;

class CartController extends Controller
{
    public function getCart(Request $request)
    {
    	$arr=[];
    	$temp_arr=[];
    	$final_arr=[];
    	$data_arr=[];

    	
    	$total=0;
    	$tax=0;
    	$grand_total=0;

    	$cart_count=Cart::where('user_id',Auth::id())
			->count();
    	$carts=Cart::where('user_id',Auth::id())
			->latest()->get()->toArray();

		if(!empty($carts))
		{
			foreach ($carts as $key => $item)
    		{
    			$temp_arr=[
    				'cart_id' => $item['id'],
    				'user_id' => $item['user_id'],
    				'package_id' => $item['package_id'],
    				'addon_service_id' => $item['addon_service_id'],
    				'company_name' => $item['company_name'],
    			];

    			$package=Package::where('id',$item['package_id'])->first();
    			$package_price=$package->price;
				$temp_arr['package']=$package;

    			if($item['addon_service_id'] != "")
    			{
    				$idsArr = explode(',',$item['addon_service_id']);
					$addonServices=AddonService::whereIn('id',$idsArr)->get()->toArray();
					$net_addon_service_price=0;
					foreach ($addonServices as $key2 => $addonService)
					{
						$addon_service_price=$addonService['price'];
						$net_addon_service_price=$net_addon_service_price+$addon_service_price;
						$temp_arr['addon_services'][]=$addonService;
					}
    			}
    			else
    			{
    				$net_addon_service_price=0;
    				$temp_arr['addon_services']=[];
    			}

    			$net_amount=$package_price+$net_addon_service_price;
				$total=$total+$net_amount;

				$temp_arr['sub_total']=number_format($net_amount,2);
				array_push($final_arr,$temp_arr);
    		}

			$grand_total=$grand_total+($total+$tax);

			$cart_total=[
				'total' => number_format($total,2),
				'tax' => $tax,
				'grand_total' => number_format($grand_total,2),
			];

			$data_arr['cart_count']=$cart_count;
			$data_arr['cart_total']=$cart_total;
			$data_arr['carts']=$final_arr;

			$arr['status']=200;
	        $arr['message']='Success!';
	        $arr['data']=$data_arr;

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

    public function addToCart(Request $request)
    {
    	$arr=[];
    	$temp_arr=[];
    	$final_arr=[];
    	$data_arr=[];
        
        $validate=Validator::make($request->all(),[
            'package_id'=>'required|integer|exists:packages,id',
            'company_name'=>'required',
        ]);

        if ($validate->fails()) 
        {
            $checker=$validate->messages()->get('*');
            if(array_key_exists('package_id',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('package_id');
                $arr['data']=NULL;
            }
            elseif(array_key_exists('company_name',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('company_name');
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
        	$data['company_name']=trim(strtolower($request->company_name));
	    	$data['user_id']=Auth::id();

	    	/*************Check Company Name**************/
	    	/*if(isset($data['company_name']))
	    	{
	    		$api = Client::make();
	    		$results = $api->searchCompany($data['company_name']);
		    	
		    	if(!$results->isEmpty())
		    	{
		    		$arr['status']=200;
	                $arr['message']=$data['company_name'] . " is not available \n This company name is too similar to one already registered with Companies House. \n Please search again for a new company name.";
	                $arr['data']=NULL;

	                return response()->json($arr,200);
		    	}
	    	}*/
	    	/********************************************/

	    	/*************Check Addon Service**************/
	    	if(isset($data['addon_service_id']) && $data['addon_service_id'] != "")
	    	{
	    		$idsArr = explode(',',$data['addon_service_id']);
	    		$checkAddonService=AddonService::whereIn('id',$idsArr)
		    		->get()->toArray();
		    	
		    	if(empty($checkAddonService))
		    	{
		    		$arr['status']=200;
	                $arr['message']='Addon Service ID not found!';
	                $arr['data']=NULL;

	                return response()->json($arr,200);
		    	}
	    	}
	    	/********************************************/

	    	$checkCart=Cart::where('user_id',Auth::id())
	    		//->where('package_id',$data['package_id'])
	    		->where('company_name',$data['company_name'])
	    		->first();
	    	
	    	if(!$checkCart)
	    	{
	    		$cart=Cart::create($data);
		    	if($cart)
		    	{
		    		$total=0;
			    	$tax=0;
			    	$grand_total=0;

			    	$cart_count=Cart::where('user_id',Auth::id())
						->count();
		    		$carts=Cart::where('user_id',Auth::id())
	    				->latest()->get()->toArray();
	    		
		    		foreach ($carts as $key => $item)
		    		{
		    			$temp_arr=[
		    				'cart_id' => $item['id'],
		    				'user_id' => $item['user_id'],
		    				'package_id' => $item['package_id'],
		    				'addon_service_id' => $item['addon_service_id'],
		    				'company_name' => $item['company_name'],
		    			];

		    			$package=Package::where('id',$item['package_id'])->first();
		    			$package_price=$package->price;
	    				$temp_arr['package']=$package;

		    			if($item['addon_service_id'] != "")
		    			{
		    				$idsArr = explode(',',$item['addon_service_id']);
							$addonServices=AddonService::whereIn('id',$idsArr)->get()->toArray();
							$net_addon_service_price=0;
							foreach ($addonServices as $key2 => $addonService)
							{
								$addon_service_price=$addonService['price'];
								$net_addon_service_price=$net_addon_service_price+$addon_service_price;
								$temp_arr['addon_services'][]=$addonService;
							}
		    			}
		    			else
		    			{
		    				$net_addon_service_price=0;
		    				$temp_arr['addon_services']=[];
		    			}

		    			$net_amount=$package_price+$net_addon_service_price;
	    				$total=$total+$net_amount;

	    				$temp_arr['sub_total']=number_format($net_amount,2);
	    				array_push($final_arr,$temp_arr);
		    		}

		    		$grand_total=$grand_total+($total+$tax);

		    		$cart_total=[
						'total' => number_format($total,2),
						'tax' => $tax,
						'grand_total' => number_format($grand_total,2),
					];

					$data_arr['cart_count']=$cart_count;
					$data_arr['cart_total']=$cart_total;
					$data_arr['carts']=$final_arr;

		    		$arr['status']=200;
	                $arr['message']='Package successfully added to cart!';
	                $arr['data']=$data_arr;

	                return response()->json($arr,200);
		    	}
		    	else
	            {
	                $arr['status']=400;
	                $arr['message']='Package not added to cart. Please try again!';
	                $arr['data']=NULL;

	                return response()->json($arr,400);
	            }
	    	}
	    	else
            {
            	$cart=Cart::where('id',$checkCart->id)
	    			->update($data);
            	if($cart)
            	{
	            	$total=0;
			    	$tax=0;
			    	$grand_total=0;

			    	$cart_count=Cart::where('user_id',Auth::id())
						->count();
	            	$carts=Cart::where('user_id',Auth::id())
		    			->latest()->get()->toArray();

		    		foreach ($carts as $key => $item)
		    		{
		    			$temp_arr=[
		    				'cart_id' => $item['id'],
		    				'user_id' => $item['user_id'],
		    				'package_id' => $item['package_id'],
		    				'addon_service_id' => $item['addon_service_id'],
		    				'company_name' => $item['company_name'],
		    			];

		    			$package=Package::where('id',$item['package_id'])->first();
		    			$package_price=$package->price;
	    				$temp_arr['package']=$package;

		    			if($item['addon_service_id'] != "")
		    			{
		    				$idsArr = explode(',',$item['addon_service_id']);
							$addonServices=AddonService::whereIn('id',$idsArr)->get()->toArray();
							$net_addon_service_price=0;
							foreach ($addonServices as $key2 => $addonService)
							{
								$addon_service_price=$addonService['price'];
								$net_addon_service_price=$net_addon_service_price+$addon_service_price;
								$temp_arr['addon_services'][]=$addonService;
							}
		    			}
		    			else
		    			{
		    				$net_addon_service_price=0;
		    				$temp_arr['addon_services']=[];
		    			}

		    			$net_amount=$package_price+$net_addon_service_price;
	    				$total=$total+$net_amount;

	    				$temp_arr['sub_total']=number_format($net_amount,2);
	    				array_push($final_arr,$temp_arr);
		    		}

		    		$grand_total=$grand_total+($total+$tax);

		    		$cart_total=[
						'total' => number_format($total,2),
						'tax' => $tax,
						'grand_total' => number_format($grand_total,2),
					];

					$data_arr['cart_count']=$cart_count;
					$data_arr['cart_total']=$cart_total;
					$data_arr['carts']=$final_arr;

	                $arr['status']=200;
	                $arr['message']='Package successfully added to cart!';
	                $arr['data']=$data_arr;

	                return response()->json($arr,200);
                }
                else
	            {
	                $arr['status']=400;
	                $arr['message']='Package not added to cart. Please try again!';
	                $arr['data']=NULL;

	                return response()->json($arr,400);
	            }
            }
        }
        catch(\Exception $e)
        {
            $arr['status']=500;
            $arr['message']='Try Again!';
            $arr['data']= NULL;

            return response()->json($arr,500);
        }
    }

    public function deleteFromCart(Request $request)
    {
    	$arr=[];
    	$temp_arr=[];
    	$final_arr=[];
    	$data_arr=[];
        
        $validate=Validator::make($request->all(),[
            'cart_id'=>'required|integer|exists:carts,id',
        ]);

        if ($validate->fails()) 
        {
            $checker=$validate->messages()->get('*');
            if(array_key_exists('cart_id',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('cart_id');
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
	    	$data['user_id']=Auth::id();
	    	
    		$cart=Cart::where('id',$data['cart_id'])
    			->delete();

    		if($cart)
    		{
    			$total=0;
		    	$tax=0;
		    	$grand_total=0;

		    	$cart_count=Cart::where('user_id',Auth::id())
					->count();
				if($cart_count > 0)
				{
		    		$carts=Cart::where('user_id',Auth::id())
	    				->latest()->get()->toArray();
	    		
		    		foreach ($carts as $key => $item)
		    		{
		    			$temp_arr=[
		    				'cart_id' => $item['id'],
		    				'user_id' => $item['user_id'],
		    				'package_id' => $item['package_id'],
		    				'addon_service_id' => $item['addon_service_id'],
		    				'company_name' => $item['company_name'],
		    			];

		    			$package=Package::where('id',$item['package_id'])->first();
		    			$package_price=$package->price;
	    				$temp_arr['package']=$package;

		    			if($item['addon_service_id'] != "")
		    			{
		    				$idsArr = explode(',',$item['addon_service_id']);
							$addonServices=AddonService::whereIn('id',$idsArr)->get()->toArray();
							$net_addon_service_price=0;
							foreach ($addonServices as $key2 => $addonService)
							{
								$addon_service_price=$addonService['price'];
								$net_addon_service_price=$net_addon_service_price+$addon_service_price;
								$temp_arr['addon_services'][]=$addonService;
							}
		    			}
		    			else
		    			{
		    				$net_addon_service_price=0;
		    				$temp_arr['addon_services']=[];
		    			}

		    			$net_amount=$package_price+$net_addon_service_price;
	    				$total=$total+$net_amount;

	    				$temp_arr['sub_total']=number_format($net_amount,2);
	    				array_push($final_arr,$temp_arr);
		    		}

		    		$grand_total=$grand_total+($total+$tax);

		    		$cart_total=[
						'total' => number_format($total,2),
						'tax' => $tax,
						'grand_total' => number_format($grand_total,2),
					];

					$data_arr['cart_count']=$cart_count;
					$data_arr['cart_total']=$cart_total;
					$data_arr['carts']=$final_arr;
				}

		    	$arr['status']=200;
                $arr['message']='Package successfully deleted from cart!';
                $arr['data']=!empty($data_arr)?$data_arr:NULL;

                return response()->json($arr,200);
    		}
    		else
            {
                $arr['status']=400;
                $arr['message']='Package not deleted from cart. Please try again!';
                $arr['data']=NULL;

                return response()->json($arr,400);
            }
        }
        catch(\Exception $e)
        {
            $arr['status']=500;
            $arr['message']='Try Again!';
            $arr['data']= NULL;

            return response()->json($arr,500);
        }
    }
}
