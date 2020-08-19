<?php

namespace App\Notifications;

use App\Http\PasswordResetController;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PlayerWalletBalanceUpdated extends Notification
{
    use Queueable;

    protected $balance;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($balance)
    {
        $this->balance = $balance;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Player wallet balance updated successfully')
            ->line('Your updated wallet balance : '.$this->balance);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
