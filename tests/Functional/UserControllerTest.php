<?php

namespace Tests\Functional;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Naweown\Events\UserAccountWasDeleted;
use Naweown\Events\UserProfileWasUpdated;
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

        $this->dontSeeInDatabase('users', ['id' => $createdUser->id]);
    }

    public function testAUserCannotDeleteAnotherUserAccount()
    {
        $createdUser = $this->modelFactoryFor(User::class);

        $troll = $this->modelFactoryFor(User::class);

        $this->be($troll);

        $this->expectsEvents(UserProfileWasViewed::class);

        $moniker = "@{$createdUser->moniker}";

        $this->get($moniker);
        $this->assertResponseOk();

        $this->delete("$moniker");

        $this->assertResponseStatus(404);

        $this->seeInDatabase('users', ['id' => $createdUser->id]);
    }

    public function testAUserCannotEditAnotherUserProfile()
    {
        $createdUser = $this->modelFactoryFor(User::class);

        $troll = $this->modelFactoryFor(User::class);

        $this->be($troll);

        $this->get("@{$createdUser->moniker}/edit");
        $this->assertResponseStatus(404);

        $this->put("@{$createdUser->moniker}");
        $this->assertResponseStatus(404);
    }

    public function testUserCanEditHisProfile()
    {
        $createdUser = $this->modelFactoryFor(User::class);

        $oldEmail = $createdUser->email;
        $oldMoniker = $createdUser->moniker;

        $this->be($createdUser);

        $this->get("@{$createdUser->moniker}");

        $this->get("@{$createdUser->moniker}/edit");
        $this->assertResponseOk();

        $this->expectsEvents(UserProfileWasUpdated::class);

        $this->put("@{$createdUser->moniker}", [
            'name' => "Lanre Adelowo",
            'email' => "me@lanreadelowo.com",
            'moniker' => "adelowo",
            'bio' => "I used to be human till i became a fiend. I swear i used to be"
        ]);

        $this->seeInDatabase('users', ['email' => "me@lanreadelowo.com", 'moniker' => "adelowo"]);

        //make sure the old profile was cleared away
        $this->dontSeeInDatabase('users', [
            'email' => $oldEmail,
            'moniker' => $oldMoniker
        ]);
    }

    public function testRuleConstrainstsWhenUpdatingProfile()
    {
        $createdUser = $this->modelFactoryFor(User::class);

        $troll = $this->modelFactoryFor(User::class);

        $this->be($createdUser);

        $this->get("@{$createdUser->moniker}");
        $this->get("@{$createdUser->moniker}/edit");

        $this->doesntExpectEvents(UserProfileWasUpdated::class);

        $this->put("@{$createdUser->moniker}", [
            'name' => "Lanre Adelowo",
            'email' => $troll->email,
        ]);

        $this->dontSeeInDatabase('users', ['email' => $troll->email, 'name' => "Lanre Adelowo"]);

        $this->put("@{$createdUser->moniker}", [
            'name' => "Lanre Adelowo",
            'moniker' => $troll->moniker,
        ]);

        $this->dontSeeInDatabase('users', ['moniker' => $troll->moniker, 'name' => "Lanre Adelowo"]);
    }
}
