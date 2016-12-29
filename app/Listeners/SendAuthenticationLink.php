<?php

namespace Naweown\Listeners;

use Naweown\Events\AccountActivationLinkWasRequested;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Naweown\Notifications\SendAccountActivationLink;

class SendAuthenticationLink
{

    public function handle(AccountActivationLinkWasRequested $event)
    {
        $user = $event->getUser();
        $user->notify(new SendAccountActivationLink($user));
    }
}
