<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AddonService;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Package;
use App\Models\PackageDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function myCurrentPackages(Request $request)
    {
    	$arr=[];
        $data=[];
        $order_array=[];
        $order_detail_array=[];
    	
    	$order_type='package';
    	$orders=Order::with(['order_details' => function($query) use($order_type) {
                $query->where('order_type',$order_type);
            }])
            ->where('user_id',Auth::id())
			->where('status','active')
			->latest()->get()->toArray();
    	
    	if(!empty($orders))
    	{
    		foreach ($orders as $key => $order) 
    		{
    			foreach ($order['order_details'] as $key2 => $order_detail) 
	    		{
                    $package=Package::where('id',$order_detail['package_id'])
                        ->select('id','title','slug','description','price',DB::raw('CASE WHEN image IS NOT NULL THEN CONCAT("'.asset('uploads/packages').'","/",image) ELSE "" END as image'))
                        ->first();

	    			$order_detail_array=[
                        'id' => $order_detail['id'],
                        'order_id' => $order_detail['order_id'],
                        'package_id' => $order_detail['package_id'],
                        'package' => $package,
                        'company_name' => $order_detail['company_name'],
                        'expire_at' => $order_detail['expire_at'],
                    ];

                    array_push($data, $order_detail_array);
	    		}
    		}
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

    public function myAdditionalServices_bk(Request $request)
    {
    	$arr=[];
        $data=[];
        $order_array=[];
        $order_detail_array=[];
        
        $order_type='addon_service';
        $orders=Order::with(['order_details' => function($query) use($order_type) {
                $query->where('order_type',$order_type);
            }])
            ->where('user_id',Auth::id())
            ->where('status','active')
            ->latest()->get()->toArray();

        if(!empty($orders))
        {
            foreach ($orders as $key => $order) 
            {
                foreach ($order['order_details'] as $key2 => $order_detail) 
                {
                    $package=Package::where('id',$order_detail['package_id'])
                        ->select('id','title','slug','description','price',DB::raw('CASE WHEN image IS NOT NULL THEN CONCAT("'.asset('uploads/packages').'","/",image) ELSE "" END as image'))
                        ->first();

                    $addon_service=AddonService::where('id',$order_detail['addon_service_id'])
                        ->select('id','title','slug','description','price')
                        ->first();

                    $order_detail_array=[
                        'id' => $order_detail['id'],
                        'order_id' => $order_detail['order_id'],
                        'package_id' => $order_detail['package_id'],
                        'package_name' => $package->title,
                        'addon_service_id' => $order_detail['addon_service_id'],
                        'addon_service' => $addon_service,
                        'company_name' => $order_detail['company_name'],
                        'expire_at' => $order_detail['expire_at'],
                    ];

                    array_push($data, $order_detail_array);
                }
            }
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

    public function myAdditionalServices(Request $request)
    {
        $arr=[];
        $data=[];
        $order_array=[];
        $order_detail_array1=[];
        $order_detail_array2=[];

        $orders=Order::where('user_id',Auth::id())
            ->where('status','active')
            ->latest()->get()->toArray();

        if(!empty($orders))
        {
            foreach ($orders as $key => $order) 
            {
                $order_array=[
                    'order_id' => $order['id'],
                    'grand_total' => $order['grand_total'],
                ];

                $order_array['packages']=[];
                $package_order_details=OrderDetail::where('order_id',$order['id'])
                    ->where('order_type','package')
                    ->get()->toArray();

                foreach ($package_order_details as $key2 => $package_order_detail) 
                {
                    $package=Package::where('id',$package_order_detail['package_id'])
                        ->select('id','title','slug','description','price',DB::raw('CASE WHEN image IS NOT NULL THEN CONCAT("'.asset('uploads/packages').'","/",image) ELSE "" END as image'))
                        ->first();

                    $order_detail_array1=[
                        'order_detail_id' => $package_order_detail['id'],
                        'company_name' => $package_order_detail['company_name'],
                        'order_type' => $package_order_detail['order_type'],
                        'expire_at' => $package_order_detail['expire_at'],
                    ];
                    $order_detail_array1['package']=$package;

                    /********OrderDetail Addon Services*********/
                    $addon_service_order_details=OrderDetail::where('order_id',$order['id'])
                        ->where('package_id',$package_order_detail['package_id'])
                        ->where('addon_service_id','!=',0)
                        ->where('company_name',$package_order_detail['company_name'])
                        ->where('order_type','addon_service')
                        ->get()->toArray();

                    $order_detail_array1['addon_services']=[];
                    foreach ($addon_service_order_details as $key3 => $addon_service_order_detail) 
                    {
                        $addon_service=AddonService::where('id',$addon_service_order_detail['addon_service_id'])
                            ->select('id','title','slug','description','price')
                            ->first();

                        $order_detail_array2=[
                            'order_detail_id' => $addon_service_order_detail['id'],
                            'company_name' => $addon_service_order_detail['company_name'],
                            'order_type' => $addon_service_order_detail['order_type'],
                            'expire_at' => $addon_service_order_detail['expire_at'],
                        ];
                        $order_detail_array2['addon_service']=$addon_service;
                        
                        array_push($order_detail_array1['addon_services'], $order_detail_array2);
                    }
                    /****************************************/ 
                    
                    array_push($order_array['packages'], $order_detail_array1);
                }

                array_push($data, $order_array);
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
        else
        {
            $arr['status']=200;
            $arr['message']='No data found!';
            $arr['data']=NULL;

            return response()->json($arr,200);
        }
    }

    public function getOrderDetails(Request $request)
    {
        $arr=[];
        $data=[];
        $order_detail_array=[];

        $validate=Validator::make($request->all(),[
            'order_id'=>'required|integer|exists:orders,id',
        ]);

        if ($validate->fails()) 
        {
            $checker=$validate->messages()->get('*');
            if(array_key_exists('order_id',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('order_id');
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

        $order=Order::where('id',$request->order_id)
            ->where('user_id',Auth::id())
            ->first();

        if(!empty($order))
        {
            /*$count_package_order_detail=OrderDetail::where('order_id',$order->id)
                ->where('order_type','package')
                ->count();

            if($count_package_order_detail > 1)
            {*/
                $data=[
                    'order_id' => $order->id,
                    'grand_total' => $order->grand_total,
                ];

                $data['packages']=[];
                $package_order_details=OrderDetail::where('order_id',$order->id)
                    ->where('order_type','package')
                    ->get()->toArray();

                foreach ($package_order_details as $key2 => $package_order_detail) 
                {
                    $package=Package::where('id',$package_order_detail['package_id'])
                        ->select('id','title','slug','description','price',DB::raw('CASE WHEN image IS NOT NULL THEN CONCAT("'.asset('uploads/packages').'","/",image) ELSE "" END as image'))
                        ->first();

                    $order_detail_array=[
                        'order_detail_id' => $package_order_detail['id'],
                        'company_name' => $package_order_detail['company_name'],
                        'order_type' => $package_order_detail['order_type'],
                        'expire_at' => $package_order_detail['expire_at'],
                    ];
                    $order_detail_array['package']=$package;

                    /********OrderDetail Addon Services*********/
                    $addon_service_order_details=OrderDetail::where('order_id',$order->id)
                        ->where('package_id',$package_order_detail['package_id'])
                        ->where('addon_service_id','!=',0)
                        ->where('company_name',$package_order_detail['company_name'])
                        ->where('order_type','addon_service')
                        ->get()->toArray();

                    $order_detail_array['addon_services']=[];
                    foreach ($addon_service_order_details as $key3 => $addon_service_order_detail) 
                    {
                        $addon_service=AddonService::where('id',$addon_service_order_detail['addon_service_id'])
                            ->select('id','title','slug','description','price')
                            ->first();

                        $order_detail_array2=[
                            'order_detail_id' => $addon_service_order_detail['id'],
                            'company_name' => $addon_service_order_detail['company_name'],
                            'order_type' => $addon_service_order_detail['order_type'],
                            'expire_at' => $addon_service_order_detail['expire_at'],
                        ];
                        $order_detail_array2['addon_service']=$addon_service;
                        
                        array_push($order_detail_array['addon_services'], $order_detail_array2);
                    }
                    /****************************************/ 
                    
                    array_push($data['packages'], $order_detail_array);
                }

            /*}
            else
            {
                $package_order_detail=OrderDetail::where('order_id',$order->id)
                    ->where('order_type','package')
                    ->first();

                $package=Package::where('id',$package_order_detail->package_id)
                        ->select('id','title','slug','description','price',DB::raw('CASE WHEN image IS NOT NULL THEN CONCAT("'.asset('uploads/packages').'","/",image) ELSE "" END as image'))
                        ->first();

                $data=[
                    'order_id' => $order->id,
                    'grand_total' => $order->grand_total,
                    'order_detail_id' => $package_order_detail->id,
                    'company_name' => $package_order_detail->company_name,
                    'expire_at' => $package_order_detail->expire_at,
                    'package' => $package,
                ];

                $addon_service_order_details=OrderDetail::where('order_id',$order->id)
                    ->where('package_id',$package_order_detail['package_id'])
                    ->where('addon_service_id','!=',0)
                    ->where('company_name',$package_order_detail['company_name'])
                    ->where('order_type','addon_service')
                    ->get()->toArray();

                $data['addon_services']=[];
                foreach ($addon_service_order_details as $key3 => $addon_service_order_detail) 
                {
                    $addon_service=AddonService::where('id',$addon_service_order_detail['addon_service_id'])
                        ->select('id','title','slug','description','price')
                        ->first();

                    $order_detail_array2=[
                        'order_detail_id' => $addon_service_order_detail['id'],
                        'company_name' => $addon_service_order_detail['company_name'],
                        'order_type' => $addon_service_order_detail['order_type'],
                        'expire_at' => $addon_service_order_detail['expire_at'],
                    ];
                    $order_detail_array2['addon_service']=$addon_service;
                    
                    array_push($data['addon_services'], $order_detail_array2);
                }
            }*/

            /*$data['addon_services']=[];
            $addon_service_order_details=OrderDetail::where('order_id',$order->id)
                ->where('order_type','addon_service')
                ->get()->toArray();

            foreach ($addon_service_order_details as $key3 => $addon_service_order_detail) 
            {
                $addon_service=AddonService::where('id',$addon_service_order_detail['addon_service_id'])
                    ->select('id','title','slug','description','price')
                    ->first();

                $order_detail_array=[
                    'order_detail_id' => $addon_service_order_detail['id'],
                    'company_name' => $addon_service_order_detail['company_name'],
                    'order_type' => $addon_service_order_detail['order_type'],
                    'expire_at' => $addon_service_order_detail['expire_at'],
                ];
                $order_detail_array['addon_service']=$addon_service;
                
                array_push($data['addon_services'], $order_detail_array);
            }*/

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
            $arr['status']=200;
            $arr['message']='No data found!';
            $arr['data']=NULL;

            return response()->json($arr,200);
        }
    }
}
