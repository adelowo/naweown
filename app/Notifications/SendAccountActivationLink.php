<?php

namespace Naweown\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Naweown\User;

class SendAccountActivationLink extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $token = $this->user->link->token;
        $uri = route('activate', $token);

        return (new MailMessage)
            ->subject("Please confirm your email address")
            ->line('In other to continue making use of our app, we require you to confirm your email address.')
            ->action('Click to confirm', $uri)
            ->line('Thanks a lot!');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
