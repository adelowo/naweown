<?php

namespace Tests\Unit;

use Naweown\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
}
