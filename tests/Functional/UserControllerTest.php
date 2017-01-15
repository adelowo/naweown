<?php

namespace Tests\Functional;

use Illuminate\Foundation\Testing\DatabaseMigrations;
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
}
