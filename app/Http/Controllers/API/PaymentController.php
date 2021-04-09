<?php

namespace App\Http\Controllers\API;

use App\Events\OrderMail;
use App\Http\Controllers\Controller;
use App\Models\AddonService;
use App\Models\Cart;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Package;
use App\Traits\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Stripe;

class PaymentController extends Controller
{
    use Common;

    public function payNow(Request $request)
    {
        $arr=[];
        $data_arr=[];

    	$validate=Validator::make($request->all(),[
            'amount'=>'required|numeric',
            'payment_status'=>'required',
            'payment_details'=>'required',
            'company_detail_id'=>'required|integer|exists:company_details,id',
            'cart_id'=>'required',
        ]);

        if ($validate->fails()) 
        {
            $checker=$validate->messages()->get('*');
            if(array_key_exists('amount',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('amount');
                $arr['data']=NULL;
            }
            elseif(array_key_exists('payment_status',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('payment_status');
                $arr['data']=NULL;
            }
            elseif(array_key_exists('payment_details',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('payment_details');
                $arr['data']=NULL;
            }
            elseif(array_key_exists('company_detail_id',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('company_detail_id');
                $arr['data']=NULL;
            }
            elseif(array_key_exists('cart_id',$checker))
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
            $user_id=Auth::id();
            $data=$request->all();
            $tax=isset($data['tax']) ? $data['tax'] : 0;
            $data['user_id']=$user_id;
            $cartIdsArr = explode(',',$data['cart_id']);
            $payment_details=json_decode($data['payment_details']);

            /*************Check Cart**************/
            $carts=Cart::whereIn('id',$cartIdsArr)->get()->toArray();
            if(empty($carts))
            {
                $arr['status']=404;
                $arr['message']='Cart ID not found!';
                $arr['data']=NULL;

                return response()->json($arr,404);
            }
            /********************************************/
            
            DB::beginTransaction(); //Begin Transaction

            /*************Order**************/
            $order_data=[
                'user_id' => $user_id,
                'company_detail_id' => $data['company_detail_id'],
                'payment_id' => !empty($payment_details->id) ? $payment_details->id : rand(),
                'total' => $data['amount'],
                'tax' => $tax,
                'grand_total' => $data['amount']+$tax,
                'order_status' => 'success',
                'payment_status' => ($data['payment_status'] == 'succeeded') ? 'paid' : $data['payment_status'],
                'payment_details' => $data['payment_details'],
            ];
            $order=Order::create($order_data);
            /************************************/

            /*************OrderDetail**************/
            foreach ($carts as $key => $cart)
            {
                $package=Package::where('id',$cart['package_id'])->first();
                $package_expire_at=date("Y-m-d H:i:s", strtotime('+'.$package->period_value.' '.$package->period_type));

                /*if($cart['addon_service_id'] != "")
                {
                    $idsArr = explode(',',$cart['addon_service_id']);
                    $addonServices=AddonService::whereIn('id',$idsArr)->get()->toArray();

                    $net_addon_service_price=0;
                    foreach ($addonServices as $key2 => $addonService)
                    {
                        $addon_service_ids_array[]=$addonService['id'];
                        $addon_service_price=$addonService['price'];
                        $net_addon_service_price=$net_addon_service_price+$addon_service_price;
                    }
                }
                else
                {
                    $net_addon_service_price=0;
                    $addon_service_ids_array=[];
                }*/

                $order_detail_data=[
                    'user_id' => $user_id,
                    'order_id' => $order->id,
                    'package_id' => $cart['package_id'],
                    'addon_service_id' => 0,
                    'package_price' => $package->price,
                    'addon_service_price' => 0,
                    'company_name' => $cart['company_name'],
                    'order_type' => 'package',
                    'expire_at' => $package_expire_at,
                ];
                $order_detail=OrderDetail::create($order_detail_data);

                if($cart['addon_service_id'] != "")
                {
                    $addon_service_ids_arr = explode(',',$cart['addon_service_id']);
                    foreach ($addon_service_ids_arr as $key3 => $addon_service_ids) 
                    {
                        $addonService2=AddonService::where('id',$addon_service_ids)->first();
                        $addon_service_expire_at=date("Y-m-d H:i:s", strtotime('+'.$addonService2->period_value.' '.$addonService2->period_type));

                        $order_detail_data2=[
                            'user_id' => $user_id,
                            'order_id' => $order->id,
                            'package_id' => $cart['package_id'],
                            'addon_service_id' => $addon_service_ids,
                            'package_price' => $package->price,
                            'addon_service_price' => $addonService2->price,
                            'company_name' => $cart['company_name'],
                            'order_type' => 'addon_service',
                            'expire_at' => $addon_service_expire_at,
                        ];
                        $order_detail=OrderDetail::create($order_detail_data2);
                    }
                }

                /*************Cart**************/
                $cartDelete=Cart::where('id',$cart['id'])->delete();
                /************************************/
            }
            /************************************/

            DB::commit(); //Commit Transaction

            if($order->id)
            {
                /********Notification*******/
                $last_order=Order::where('id',$order->id)->first();
                $order_details=OrderDetail::where('order_id',$order->id)
                    ->where('order_type','package')
                    ->get()->toArray();

                if(!empty($order_details))
                {
                    foreach ($order_details as $key4 => $p_order_detail) 
                    {
                        $package=Package::where('id',$p_order_detail['package_id'])
                            ->select('id','title','slug','description','price',DB::raw('CASE WHEN image IS NOT NULL THEN CONCAT("'.asset('uploads/packages').'","/",image) ELSE "" END as image'))
                            ->first();
                        
                        $title="You have purchased ".$package->title." Package of ".$p_order_detail['company_name']." company";

                        $notification_data=[
                            'user_id' => $user_id,
                            'order_id' => $p_order_detail['order_id'],
                            'order_detail_id' => $p_order_detail['id'],
                            'title' => $title,
                            'description' => NULL,
                            'company_name' => $p_order_detail['company_name'],
                            'type' => 'payment',
                        ];
                        $notification=Notification::create($notification_data);
                        if($notification)
                        {
                            $get_notification=Notification::where('id',$notification->id)
                                ->first();

                            /********Send Notification******/
                            if($this->countUserMeta($user_id,'device_token') > 0)
                            {
                                $device_token=$this->getUserMeta($user_id,'device_token')->meta_value;
                                $title="Your Order No : ".$order->id;
                                $body=$get_notification;
                                $this->sendNotification($device_token,$title,$body);
                            }
                            /*********************************/
                        }
                    }
                }
                /**************************/

                /********Send Order Mail******/
                $orderData=[
                    'orders' => $this->getOrderDetails($order->id, $user_id),
                    'email' => Auth::user()->email,
                ];

                event(new OrderMail($orderData));
                /********************************/
            }
            
            $data_arr=[
                'order_id' => $order->id,
            ];

            $arr['status']=200;
            $arr['message']='Thank you for your order!';
            $arr['data']=$data_arr;

            return response()->json($arr,200);
	    	
        }
        catch(\Exception $e)
        {
            DB::rollBack(); // Rollback

            $arr['status']=500;
            $arr['message']=$e->getMessage();
            $arr['data']= NULL;

            return response()->json($arr,500);
        }
    }

    public function generateClientSecret(Request $request)
    {
        $arr=[];
        $output=[];

        $validate=Validator::make($request->all(),[
            'amount'=>'required|numeric',
        ]);

        if ($validate->fails()) 
        {
            $checker=$validate->messages()->get('*');
            if(array_key_exists('amount',$checker))
            {
                $arr['status']=422;
                $arr['message']=$validate->errors()->first('amount');
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

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try 
        {
            $data=$request->all();
            $data['user_id']=Auth::id();

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $data['amount'] * 100,
                'currency' => 'usd',
            ]);

            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];

            $arr['status']=200;
            $arr['message']='Success!';
            $arr['data']=$output;

            return response()->json($arr,200);

        } 
        catch (\Exception $e) 
        {
            $arr['status']=500;
            $arr['message']=$e->getMessage();
            $arr['data']= NULL;

            return response()->json($arr,500);
        }
    }
}
