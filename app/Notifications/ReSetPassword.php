<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class ReSetPassword extends Notification
{
    use Queueable;
    protected $verification_code;

    public function __construct($verification_code)
    {
        $this->verification_code=$verification_code;
    }

    protected function ReSetPassword($notifiable)
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
                       ->line('You are receiving this email because we received a password reset request for your account.')
                       ->line('Reset Password code is : '. ''.$this->verification_code)
                       ->line('If you did not request a password reset, no further action is required.');
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
