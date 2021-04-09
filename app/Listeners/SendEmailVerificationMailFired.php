<?php

namespace App\Listeners;

use App\Events\SendEmailVerificationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class SendEmailVerificationMailFired
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
     * @param  SendEmailVerificationMail  $event
     * @return void
     */
    public function handle(SendEmailVerificationMail $event)
    {
        $user = $event->user;
        if(!empty($user->email))
        {
            Mail::to($user->email)->send(new \App\Mail\SendEmailVerificationMail($user));
        }
    }
}
