<?php

namespace Naweown\Listeners;

use Naweown\Events\CategoryWasViewed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncrementCategoryViewCount
{

    public function handle(CategoryWasViewed $event)
    {
        return $event->getCategory()
            ->increment('number_of_views');
    }
}
