<?php

namespace Tests\Functional\One;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Naweown\Item;
use Naweown\User;
use Tests\TestCase;

class ItemControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testCanGetAllItems()
    {

        $user = $this->getUserWithApiToken();

        $this->get('api/v1/items?api_token=' . $user->api_token);

        $this->assertResponseOk();
    }

    protected function getUserWithApiToken()
    {
        $user = $this->modelFactoryFor(\Naweown\User::class);
        $user->api_token = $this->app['token.generator.api']->generate();
        $user->save();

        return $user;
    }

    public function testCanFetchAllItemsForASpecificUser()
    {
        $user = $this->getUserWithApiToken();

        $this->createItems($user);

        $this->get("api/v1/users/@{$user->moniker}/items?api_token={$user->api_token}");

        $this->assertResponseOk();
    }

    protected function createItems(User $user)
    {
        return $user->item()
            ->save(
                new Item([
                        'title' => "Some cool title",
                        'description' => "THe return of the Jedi",
                        'cats' => 'dark-force',
                        'slug' => 'oops-oops',
                        'images' => ''
                    ]
                )
            );
    }
}
