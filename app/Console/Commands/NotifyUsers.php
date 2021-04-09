<?php

namespace App\Console\Commands;

use App\Mail\SendNotificationMail;
use App\Models\AddonService;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Package;
use App\Traits\Common;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class NotifyUsers extends Command
{
    use Common;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = date('Y-m-d');
        $orders=Order::with(['user','order_details' => function($query) use($date) {
                $query->whereDate('expire_at','>=',$date);
            }])
            ->where('status','active')
            ->latest()->get()->toArray();
        
        if(!empty($orders))
        {
            foreach ($orders as $key => $order) 
            {
                foreach ($order['order_details'] as $key2 => $order_detail) 
                {
                    $date1 = $order_detail['expire_at'];
                    $date2 = date('Y-m-d H:i:s');
                    $date_difference = date_difference($date1,$date2);

                    if($date_difference > 0 && ($date_difference == 30 || $date_difference == 15 || $date_difference == 7 || $date_difference == 1))
                    {
                        $package=Package::where('id',$order_detail['package_id'])
                            ->select('id','title','slug','description','price',DB::raw('CASE WHEN image IS NOT NULL THEN CONCAT("'.asset('uploads/packages').'","/",image) ELSE "" END as image'))
                            ->first();
                        
                        $title="Your ".$package->title." Package is going to expire in ".$date_difference." days";

                        if($order_detail['addon_service_id'] != 0)
                        {
                            $addon_service=AddonService::where('id',$order_detail['addon_service_id'])
                                ->select('id','title','slug','description','price')
                                ->first();

                            $title="Your ".$addon_service->title." Service of ".$package->title." Package is going to expire in ".$date_difference." days";
                        }

                        $data=[
                            'user_id' => $order['user_id'],
                            'order_id' => $order_detail['order_id'],
                            'order_detail_id' => $order_detail['id'],
                            'title' => $title,
                            'description' => NULL,
                            'company_name' => $order_detail['company_name'],
                            'type' => 'expire',
                        ];

                        $notification=Notification::create($data);
                        if($notification)
                        {
                            $get_notification=Notification::where('id',$notification->id)
                                ->first();

                            /********Send Notification******/
                            if($this->countUserMeta($order['user_id'],'device_token') > 0)
                            {
                                $device_token=$this->getUserMeta($order['user_id'],'device_token')->meta_value;
                                $title=$title;
                                $body=$get_notification;
                                $this->sendNotification($device_token,$title,$body);
                            }
                            /******************************************/

                            /********Send Notification Mail******/
                            $mail_data=[
                                'email' => $order['user']['email'],
                                'company_name' => $order_detail['company_name'],
                                'package_name' => $package->title,
                                'title' => $title,
                            ];
                            Mail::to($order['user']['email'])->send(new SendNotificationMail($mail_data));
                            /******************************************/
                        }
                    }
                }
            }
        }
    }
}
