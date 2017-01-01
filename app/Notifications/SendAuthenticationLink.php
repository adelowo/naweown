<?php

namespace Naweown\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Naweown\User;

class SendAuthenticationLink extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    protected $link;

    public function __construct(User $user, string $link)
    {
        $this->user = $user;
        $this->link;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $name = $this->user->name;

        return (new MailMessage)
            ->subject("Here is your magic link to login")
            ->line("Hello,{$name}, You requested for a login link some seconds ago")
            ->action('Click to login', $this->link)
            ->line('Thank you for using Naweown!');
    }
}
