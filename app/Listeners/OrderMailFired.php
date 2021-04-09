<?php

namespace App\Listeners;

use App\Events\OrderMail;
use App\Mail\SendOrderMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class OrderMailFired
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderMail  $event
     * @return void
     */
    public function handle(OrderMail $event)
    {
        $order=$event->order;
        $orders=$order['orders'];
        
        if(!empty($order['email']))
        {
            Mail::to($order['email'])->send(new SendOrderMail($orders));
        }
    }
}
