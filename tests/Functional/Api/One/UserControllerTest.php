<?php

namespace Tests\Functional\One;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    use DatabaseMigrations;

    public function testCanGetAllUsers()
    {

        $user = $this->getUserWithApiToken();

        $this->get('api/v1/users?api_token=' . $user->api_token);

        $this->assertResponseOk();
    }

    protected function getUserWithApiToken()
    {
        $user = $this->modelFactoryFor(\Naweown\User::class);
        $user->api_token = $this->app['token.generator.api']->generate();
        $user->save();

        return $user;
    }

    public function testCanFetchOnlyOneUser()
    {
        $user = $this->getUserWithApiToken();

        $this->get('api/v1/users/@' . $user->moniker . '?api_token=' . $user->api_token);

        $this->seeJsonStructure([
            'id',
            'name',
            'moniker',
            'email',
            'bio',
            'created_at',
            'updated_at'
        ]);
    }
}
