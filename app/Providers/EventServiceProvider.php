<?php

namespace App\Providers;

use App\Events\ContactMail;
use App\Events\OrderMail;
use App\Events\SendEmailVerificationMail;
use App\Events\SendForgotPasswordMail;
use App\Listeners\ContactMailFired;
use App\Listeners\OrderMailFired;
use App\Listeners\SendEmailVerificationMailFired;
use App\Listeners\SendForgotPasswordMailFired;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        SendEmailVerificationMail::class => [
            SendEmailVerificationMailFired::class,
        ],

        SendForgotPasswordMail::class => [
            SendForgotPasswordMailFired::class,
        ],

        OrderMail::class => [
            OrderMailFired::class,
        ],

        ContactMail::class => [
            ContactMailFired::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
