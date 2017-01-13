<?php

namespace Naweown\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Naweown\Category;

class CategoryWasViewed
{
    use InteractsWithSockets, SerializesModels;

    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getCategory() : Category
    {
        return $this->category;
    }
}
