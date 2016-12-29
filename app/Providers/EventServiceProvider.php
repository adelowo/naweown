<?php

namespace Naweown\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Naweown\Events\AuthenticationLinkWasRequested;
use Naweown\Events\UserWasCreated;
use Naweown\Listeners\SendAuthenticationLink;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        UserWasCreated::class => [],
        AuthenticationLinkWasRequested::class => [SendAuthenticationLink::class]
    ];

    public function boot()
    {
        parent::boot();
    }
}
