<?php

namespace Tests\Functional;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Naweown\Events\UserAccountWasDeleted;
use Naweown\Events\UserProfileWasViewed;
use Naweown\User;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    use DatabaseMigrations;

    public function testUsersRouteIsUpAndRunning()
    {
        $this->get('users');
        $this->assertResponseOk();
    }

    public function testCanVisitAUsersProfilePage()
    {
        $createdUser = $this->modelFactoryFor(User::class);

        $this->expectsEvents(UserProfileWasViewed::class);

        $this->get("@{$createdUser->moniker}");
        $this->assertResponseOk();
    }

    public function testUsersProfileRouteBindings()
    {
        $this->get('@unexistent');
        $this->assertResponseStatus(404);
    }

    public function testUsersCanDeleteTheirAccount()
    {
        $createdUser = $this->modelFactoryFor(User::class);

        $this->be($createdUser);

        $this->expectsEvents(
            [
                UserProfileWasViewed::class,
                UserAccountWasDeleted::class
            ]
        );

        $moniker = "@{$createdUser->moniker}";

        $this->get($moniker);
        $this->assertResponseOk();

        $this->delete("$moniker");

        $this->assertRedirectedToRoute('logout');
    }
}
