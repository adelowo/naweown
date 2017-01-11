<?php

use Tests\TestCase;
use Naweown\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ItemControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testItemCanBeCreated()
    {
        $createdUser = $this->modelFactoryFor(User::class);

        $createdUser->activateAccount();

        $this->be($createdUser);

        $this->visit('items/create')
            ->type('some cool product', 'title')
            ->type(
                'cool product\'s description here. What else were you hoping to find here ?',
                'description'
            )
            ->press('Add Item')
            ->seeRouteIs('items.show', 1);
    }
}
