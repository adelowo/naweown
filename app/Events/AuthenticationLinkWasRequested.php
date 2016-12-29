<?php

namespace Naweown\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Naweown\User;

class AuthenticationLinkWasRequested
{
    use InteractsWithSockets, SerializesModels;

    protected $user;

    protected $shouldRememberUserAfterLoggingIn;

    public function __construct(User $user, bool $shouldRemember = false)
    {
        $this->user = $user;
        $this->shouldRememberUserAfterLoggingIn = $shouldRemember;
    }

    public function getUser() : User
    {
        return $this->user;
    }

    public function isShouldRememberUserAfterLoggingIn(): bool
    {
        return $this->shouldRememberUserAfterLoggingIn;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
