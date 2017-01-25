<?php

namespace Naweown\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Naweown\Services\ApiTokenGenerator;
use Naweown\Services\MagicLinkTokenGenerator;
use Naweown\Services\TokenGeneratorInterface;
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

        Validator::extend(
            'slug',
            function (string $attribute, string $value, array $parameters, $validator) {
                return (bool)preg_match('/-/', $value);
            }
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //The magic link generator is the default generator
        //Even though there is going to be an ApiTokenGenerator.
        $this->app->bind(TokenGeneratorInterface::class, function (Application $application) {
            return new MagicLinkTokenGenerator();
        });

        $this->app->bind('token.generator.api', function (Application $application) {
            return new ApiTokenGenerator();
        });

        $this->app->bind('token.generator.magic', function (Application $application) {
            return new MagicLinkTokenGenerator();
        });
    }
}
