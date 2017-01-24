<?php

namespace Naweown\Providers;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Naweown\Events\AccountActivationLinkWasRequested;
use Naweown\Events\AuthenticationLinkWasRequested;
use Naweown\Events\CategoryWasViewed;
use Naweown\Events\ItemWasViewed;
use Naweown\Events\UserAccountWasDeleted;
use Naweown\Events\UserProfileWasUpdated;
use Naweown\Events\UserProfileWasViewed;
use Naweown\Events\UserWasCreated;
use Naweown\Listeners\IncrementCategoryViewCount;
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
        ],
        CategoryWasViewed::class => [
            IncrementCategoryViewCount::class
        ],
        UserProfileWasViewed::class => [

        ],
        UserAccountWasDeleted::class => [

        ],
        UserProfileWasUpdated::class => [

        ]
    ];

    public function boot()
    {
        parent::boot();
    }
}
