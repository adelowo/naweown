<?php

namespace Naweown\Providers;

use Illuminate\Foundation\Application;
use Naweown\Services\TokenGenerator;
use Naweown\Services\TokenGeneratorInterface;
use Illuminate\Support\ServiceProvider;
use Naweown\Token;
use Naweown\User;

class AppServiceProvider extends ServiceProvider
{

    public function boot(TokenGeneratorInterface $tokenGenerator)
    {

        User::created(function (User $user) use ($tokenGenerator) {
            $token = new Token(['token' => $tokenGenerator->generate()]);
            $user->token()->save($token);
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TokenGeneratorInterface::class, function (Application $application) {
            return new TokenGenerator();
        });
    }
}
