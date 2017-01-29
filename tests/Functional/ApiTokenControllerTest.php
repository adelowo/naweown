<?php

namespace Tests\Functional;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Naweown\User;
use Tests\TestCase;

class ApiTokenControllerTest extends TestCase
{

    use DatabaseMigrations;

    public function testATokenCanBeCreated()
    {

        $user = $this->modelFactoryFor(User::class);
        $this->be($user);

        $this->post("@$user->moniker/token");

        $user = User::findByMoniker($user->moniker);

        $this->assertRedirectedTo("@$user->moniker");

        $this->assertTrue(
            $user->hasApiToken()
        );
    }

    public function testAUserCannotCreateMultipleTokens()
    {

        $troll = $this->modelFactoryFor(User::class);
        $this->be($troll);

        $this->post("@$troll->moniker/token");
        $this->assertRedirectedTo("@$troll->moniker");

        $this->get('/'); //play around the UI
        $this->post("@$troll->moniker/token");

        $this->assertRedirectedTo('/'); //must redirect the user
    }

    public function testOnlyPostAndPutMethodsAreAllowed()
    {
        $troll = $this->modelFactoryFor(User::class);
        $this->be($troll);

        $this->get("@$troll->moniker/token");
        $this->assertResponseStatus(405);

        $this->delete("@$troll->moniker/token");
        $this->assertResponseStatus(405);
    }

    public function testTokensCanOnlyBeUpdatedIfTheUserAlreadyOwnOne()
    {
        $troll = $this->modelFactoryFor(User::class);

        $this->be($troll);

        $this->get("@{troll->moniker}");

        $this->put("@{$troll->moniker}/token");

        $this->assertRedirectedTo("@$troll->moniker");
    }

    public function testAUserCanUpdateHisApiToken()
    {
        $user = $this->modelFactoryFor(User::class);

        $this->be($user);

        $moniker = $user->moniker;

        $this->get("@{$moniker}");

        $this->put("@{$moniker}/token");
        $this->assertRedirectedToRoute('users.profile', $moniker);
    }
}
