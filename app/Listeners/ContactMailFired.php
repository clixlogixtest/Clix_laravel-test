<?php

namespace App\Listeners;

use App\Events\ContactMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class ContactMailFired
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
     * @param  ContactMail  $event
     * @return void
     */
    public function handle(ContactMail $event)
    {
        $contact = $event->contact;
        Mail::send('emails.SendContactMail', $contact, function($message) use ($contact) {
            $message->from($contact['email']);
            $message->to('admin@admin.com', 'Admin')->subject($contact['subject']);
        });
    }
}
