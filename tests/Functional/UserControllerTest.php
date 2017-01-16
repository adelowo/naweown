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

    public function testCanViewAUserRelationshipWithOtherUsers()
    {
        $user = $this->modelFactoryFor(User::class);

        $route = "@{$user->moniker}";

        $this->get($route . '/followers');
        $this->assertResponseOk();

        $this->get($route . '/follows');
        $this->assertResponseOk();

        $this->get("$route/followerss");
        $this->assertResponseStatus(404);
    }

    public function testCanFollowAUser()
    {

        list($firstUser, $secondUser) = $this->setUpUsers();

        $this->post("@{$firstUser->moniker}/follow");

        $result = $this->seeInDatabase('followers', ['user_id' => $firstUser->id, 'follower_id' => $secondUser->id]);
        $this->assertRedirectedTo("@{$firstUser->moniker}");
        $this->assertSessionHas('user.followed', true);


        $this->assertCount(1, $result);
    }

    protected function setUpUsers()
    {
        $firstUser = $this->modelFactoryFor(User::class);

        $secondUser = $this->modelFactoryFor(User::class);

        $this->be($secondUser);

        return [$firstUser, $secondUser];
    }

    public function testCanFollowAUserOnceOnly()
    {
        list($firstUser, $secondUser) = $this->setUpUsers();

        $this->post("@{$firstUser->moniker}/follow");

        $this->post("@{$firstUser->moniker}/follow");

        $result = $this->seeInDatabase(
            'followers',
            ['user_id' => $firstUser->id, 'follower_id' => $secondUser->id]
        );

        $this->assertCount(1, $result);

        $this->assertSessionHas('user.relationship.failed');
    }

    public function testAnUnauthenticatedUserCannotModifyHisRelationWithAnotherUser()
    {
        //Modification here means "follow" or "unfollow"

        list($firstUser, $secondUser) = $this->setUpUsers();

        $this->get('logout');

        $this->post("@$firstUser->moniker/follow");
        $this->assertRedirectedToRoute('login');

        $this->post("@$firstUser->moniker/unfollow");
        $this->assertRedirectedToRoute('login');
    }
}
