<?php

namespace Naweown\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Naweown\Services\TokenGeneratorInterface;
use Naweown\Events\AuthenticationLinkWasRequested;
use Naweown\Notifications\SendAuthenticationLink as AuthenticationNotifier;
use Naweown\Token;
use Naweown\User;

class SendAuthenticationLink
{

    protected $tokenGenerator;

    public function __construct(TokenGeneratorInterface $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    public function handle(AuthenticationLinkWasRequested $event)
    {
        $user = $event->getUser();

        $token = $this->tokenGenerator->generate();

        $this->dropStaleTokens($user);
        $this->generateNewToken($user, $token);

        if ($event->isShouldRememberUserAfterLoggingIn()) {
            //If this GET param is found on the uri link, then
            //The user would be "auto-remembered" by the login system
            $token .= '&remember=1';
        }

        $user->notify(
            new AuthenticationNotifier(
                $user,
                route('login.token', $token)
            )
        );
    }

    protected function dropStaleTokens(User $user)
    {
        Token::where('user_id', $user->id)
            ->delete();
    }

    protected function generateNewToken(User $user, string $token)
    {
        return $user->token()->save(
            new Token(['token' => $token])
        );
    }
}
