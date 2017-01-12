<?php

namespace Naweown\Listeners;

use Naweown\Events\ItemWasViewed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncrementItemViewCount
{

    public function handle(ItemWasViewed $event)
    {
        return $event->getItem()
            ->increment('number_of_views');
    }
}
