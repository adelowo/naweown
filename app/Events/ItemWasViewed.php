<?php

namespace Naweown\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Naweown\Item;

class ItemWasViewed
{
    use InteractsWithSockets, SerializesModels;

    protected $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    public function getItem() : Item
    {
        return $this->item;
    }
}
