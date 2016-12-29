<?php

namespace Tests\Functional\Auth;

use Illuminate\Support\Facades\Request;
use Naweown\Events\AccountActivationLinkWasRequested;
use Naweown\Events\AuthenticationLinkWasRequested;
use Naweown\Events\UserWasCreated;
use Naweown\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterControllerTest extends TestCase
{

    use DatabaseMigrations;

    public function testPageIsUpAndRunning()
    {
        $this->get('register');

        $this->assertResponseOk();
    }

    public function testLoggedInUsersCannotVisitThisPage()
    {
        $user = factory(User::class)->make();

        $this->actingAs($user);

        $this->get('register');
        $this->assertRedirectedTo('/');
    }

    public function testUserCanSuccessfullyRegister()
    {

        $this->expectsEvents([UserWasCreated::class, AccountActivationLinkWasRequested::class]);

        $data = ['moniker' => 'therealclown', 'name' => "Lanre Adelowo", 'email' => 'thereal@clown.com'];

        $this->post('register', $data);

        $this->assertRedirectedTo('/');
    }

    /**
     * @dataProvider getInvalidValues
     */
    public function testValidationFailsBecauseOfInvalidValues($value)
    {
        $this->doesntExpectEvents([UserWasCreated::class, AccountActivationLinkWasRequested::class]);

        $this->post('register', $value);

        $this->assertSessionHasErrors();
        $this->assertHasOldInput();
        $this->assertResponseStatus(302);
    }

    public function getInvalidValues()
    {
        return [
            [
                [
                    'name' => "Lanre Adelowo",
                    "email" => "oops",
                    "moniker" => "adelowo"
                ]
            ],
            [
                [
                    'name' => "La",
                    "email" => "me@lanreadelowo.com",
                    "moniker" => "adelowo"
                ]
            ],
            [
                [
                    'name' => "Lanre Adelowo",
                    "email" => "me@lanreadelowo.com",
                    "moniker" => "ad"
                ]
            ]
        ];
    }
}
