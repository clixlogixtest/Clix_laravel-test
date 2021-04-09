<?php

namespace App\Listeners;

use App\Events\SendForgotPasswordMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class SendForgotPasswordMailFired
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
     * @param  SendForgotPasswordMail  $event
     * @return void
     */
    public function handle(SendForgotPasswordMail $event)
    {
        $user = $event->user;
        if(!empty($user->email))
        {
            Mail::to($user->email)->send(new \App\Mail\SendForgotPasswordMail($user));
        }
    }
}
