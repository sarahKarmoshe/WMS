<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class SetPasswordFirstTime extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($verification_code)
    {
        $this->verification_code=$verification_code;
    }

    protected function SetPasswordFirstTime($notifiable)
    {
        return URL::temporarySignedRoute(
            "verificationapi.verify", Carbon::now()->addMinutes(60), ["id" => $notifiable->getKey()]
        ); // this will basically mimic the email endpoint with get request
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
            ->line('You are receiving this email because we received an Account verify request from you .')
            ->line('You have to set password In order to complete your account confirmation process .')
            ->line('set Password code is : '. ''.$this->verification_code);
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
