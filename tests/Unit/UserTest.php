<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Naweown\User;
use Tests\TestCase;

class UserTest extends TestCase
{

    use DatabaseMigrations;

    public function testAUserCanBeFoundByEmailAddress()
    {
        $createdUser = $this->modelFactoryFor(User::class);

        $user = User::findByEmailAddress($createdUser->email);

        $this->assertEquals($createdUser->email, $user->email);
    }

    public function testAnExceptionIsThrownWhenTryingToFindAUserByANonExistentEmailAddress()
    {
        $this->expectException(ModelNotFoundException::class);

        User::findByEmailAddress("i_love@z.sh");
    }

    public function testUserAccountActivationStatus()
    {
        $createdUser = $this->modelFactoryFor(User::class);

        $createdUser->update([
            'is_email_validated' => User::EMAIL_VALIDATED
        ]);

        $this->assertTrue($createdUser->isAccountActivated());
    }

    public function testUserAccountActivationStatusIsInTheNegative()
    {
        $createdUser = $this->modelFactoryFor(User::class);

        $this->assertFalse($createdUser->isAccountActivated());
    }

    public function testFindUserByMoniker()
    {
        $createdUser = $this->modelFactoryFor(User::class);

        $user = User::findByMoniker($createdUser->moniker);

        $this->assertSame($createdUser->email, $user->email);
    }

    public function testCannotFindUserByAGivenMoniker()
    {
        $this->expectException(ModelNotFoundException::class);

        User::findByMoniker("dream-moniker");
    }

    public function testHasApiTokenIsInTheNegative()
    {
        $createdUser = $this->modelFactoryFor(User::class);

        $this->assertFalse(
            User::findByMoniker($createdUser->moniker)
                ->hasApiToken()
        );
    }

    public function testCanFindAUserByAnApiToken()
    {
        $createdUser = $this->modelFactoryFor(User::class);

        $token = $this->app['token.generator.api']->generate();

        $createdUser->api_token = $token;
        $createdUser->save();

        $dbUser = User::findByApiToken($token);

        $this->assertEquals(
            $createdUser->id,
            $dbUser->id
        );

        $this->assertEquals(
            $createdUser->token,
            $dbUser->token
        );
    }
}
