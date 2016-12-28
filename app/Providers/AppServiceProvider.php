<?php

namespace Naweown\Providers;

use Illuminate\Foundation\Application;
use Naweown\Services\TokenGenerator;
use Naweown\Services\TokenGeneratorInterface;
use Illuminate\Support\ServiceProvider;
use Naweown\Link;
use Naweown\User;

class AppServiceProvider extends ServiceProvider
{

    public function boot(TokenGeneratorInterface $tokenGenerator)
    {

        User::created(function (User $user) use ($tokenGenerator) {

            $link = new Link(['token' => $tokenGenerator->generate()]);

            $user->link()->save($link);

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
