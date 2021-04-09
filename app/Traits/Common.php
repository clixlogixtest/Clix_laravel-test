<?php

namespace App\Traits;
use App\Models\AddonService;
use App\Models\Country;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Package;
use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

trait Common
{
	public function checkUserMetaDeviceToken($user_id,$meta_key,$meta_value)
	{
		$count = UserMeta::where('user_id',$user_id)
			->where('meta_key',$meta_key)
			->where('meta_value',$meta_value)
			->count();

		return $count;
	}

	public function deleteUserMetaDeviceToken($user_id,$meta_key,$meta_value)
	{
		$delete = UserMeta::where('user_id',$user_id)
			->where('meta_key',$meta_key)
			->where('meta_value',$meta_value)
			->delete();

		return $delete;
	}

	public function countUserMeta($user_id,$meta_key)
	{
		$count = UserMeta::where('user_id',$user_id)
			->where('meta_key',$meta_key)
			->count();

		return $count;
	}

	public function getUserMeta($user_id,$meta_key)
	{
		$data = UserMeta::where('user_id',$user_id)
			->where('meta_key',$meta_key)
			->first();

		return $data;
	}

	public function addUserMeta($user_id,$meta_key,$meta_value)
	{
		$array=[
            'user_id' => $user_id,
            'meta_key' => $meta_key,
            'meta_value' => $meta_value,
        ];

		$userMeta = $this->getUserMeta($user_id,$meta_key);
		if($userMeta)
		{
			$data = UserMeta::where('id',$userMeta->id)->update($array);
		}
		else
		{
			$data = UserMeta::create($array);
		}
		return $data;
	}

	public function getUserMetaData($user_id)
	{
		$temp=[];
		
		if($this->countUserMeta($user_id,'first_name') > 0)
		{
			$temp['first_name']=$this->getUserMeta($user_id,'first_name')->meta_value;
		}
		else
		{
			$temp['first_name']='';
		}

		if($this->countUserMeta($user_id,'last_name') > 0)
		{
			$temp['last_name']=$this->getUserMeta($user_id,'last_name')->meta_value;
		}
		else
		{
			$temp['last_name']='';
		}

		if($this->countUserMeta($user_id,'job_title') > 0)
		{
			$temp['job_title']=$this->getUserMeta($user_id,'job_title')->meta_value;
		}
		else
		{
			$temp['job_title']='';
		}

		if($this->countUserMeta($user_id,'nationality') > 0)
		{
			$temp['nationality']=$this->getUserMeta($user_id,'nationality')->meta_value;
		}
		else
		{
			$temp['nationality']='';
		}

		if($this->countUserMeta($user_id,'date_of_birth') > 0)
		{
			$temp['date_of_birth']=$this->getUserMeta($user_id,'date_of_birth')->meta_value;
		}
		else
		{
			$temp['date_of_birth']='';
		}

		if($this->countUserMeta($user_id,'town_of_birth') > 0)
		{
			$temp['town_of_birth']=$this->getUserMeta($user_id,'town_of_birth')->meta_value;
		}
		else
		{
			$temp['town_of_birth']='';
		}

		if($this->countUserMeta($user_id,'gender') > 0)
		{
			$temp['gender']=$this->getUserMeta($user_id,'gender')->meta_value;
		}
		else
		{
			$temp['gender']='';
		}

		if($this->countUserMeta($user_id,'device_token') > 0)
		{
			$temp['device_token']=$this->getUserMeta($user_id,'device_token')->meta_value;
		}
		else
		{
			$temp['device_token']='';
		}
		
		return $temp;
	}

	public function getCountry($country_id)
	{
		$country = Country::where('id',$country_id)->first();
		return $country;
	}

	public function getOrderDetails($order_id, $user_id)
	{
        $data=[];
        $order_detail_array=[];

        $order=Order::where('id',$order_id)
            ->where('user_id',$user_id)
            ->first();

        if(!empty($order))
        {
            $data=[
                'order_id' => $order->id,
                'grand_total' => $order->grand_total,
            ];

            $data['order_details']=[];
            $order_details=OrderDetail::where('order_id',$order->id)
                ->get()->toArray();

            foreach ($order_details as $key2 => $order_detail) 
            {
                $package=Package::where('id',$order_detail['package_id'])
                    ->select('id','title','slug','description','price',DB::raw('CASE WHEN image IS NOT NULL THEN CONCAT("'.asset('uploads/packages').'","/",image) ELSE "" END as image'))
                    ->first();

                if($order_detail['addon_service_id'] != 0)
                {
                	$addon_service=AddonService::where('id',$order_detail['addon_service_id'])
                        ->select('id','title','slug','description','price')
                        ->first();
                }
                else
                {
                	$addon_service="";
                }
                
                $order_detail_array=[
                    'order_detail_id' => $order_detail['id'],
                    'package_name' => $package->title,
                    'addon_service_name' => ($addon_service != "") ? $addon_service->title : 'N/A',
                    'package_price' => $order_detail['package_price'],
                    'addon_service_price' => $order_detail['addon_service_price'],
                    'company_name' => $order_detail['company_name'],
                    'order_type' => $order_detail['order_type'],
                    'expire_at' => $order_detail['expire_at'],
                ];
                
                array_push($data['order_details'], $order_detail_array);
            }
        }
        
        return $data;
	}

	public function sendNotification($device_token, $title, $body)
    {
    	$data = [];

    	$server_key = env('FIREBASE_SERVER_KEY');
        $url = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            "title" => $title,
            "body" => $body,  
        ];

        $data = [
            "to" => $device_token,
            "notification" => $notification,
            "data" => $notification,
        ];
        $payload = json_encode($data);
    
        $headers = [
            'Authorization: key=' . $server_key,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
               
        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response,true);
  
        //print_r($result);die;
		if(!empty($result['success']))
		{
			return true;
		}
		else
		{
			return false;
		}
    }

    public function sendNotifications($device_token_array, $title, $body)
    {
    	$data = [];
    	
    	$server_key = env('FIREBASE_SERVER_KEY');
        $url = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            "title" => $title,
            "body" => $body,  
        ];

        $data = [
            "registration_ids" => $device_token_array, //multple token array
            "notification" => $notification,
            "data" => $notification,
        ];
        $payload = json_encode($data);
    
        $headers = [
            'Authorization: key=' . $server_key,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
               
        $response = curl_exec($ch);
        $result = json_decode($response,true);

        //print_r($result);die;
		if(!empty($result['success']))
		{
			return true;
		}
		else
		{
			return false;
		}
    }
}
 