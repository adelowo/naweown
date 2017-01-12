<?php

namespace Naweown\Providers;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Naweown\Events\AccountActivationLinkWasRequested;
use Naweown\Events\AuthenticationLinkWasRequested;
use Naweown\Events\ItemWasViewed;
use Naweown\Events\UserWasCreated;
use Naweown\Listeners\IncrementItemViewCount;
use Naweown\Listeners\SendAuthenticationLink;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        UserWasCreated::class => [

        ],
        AuthenticationLinkWasRequested::class => [
            SendAuthenticationLink::class
        ],
        AccountActivationLinkWasRequested::class => [],
        ItemWasViewed::class => [
            IncrementItemViewCount::class
        ]
    ];

    public function boot()
    {
        parent::boot();
    }
}
