<?php

namespace Tests\Functional\Auth;

use Naweown\Events\AuthenticationLinkWasRequested;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginControllerTest extends TestCase
{

    use DatabaseMigrations;

    public function testPageIsUpAndRunning()
    {
        $this->get('login');

        $this->assertResponseOk();
    }

    public function testALoggedInUserCannotVisitThisPage()
    {
        $this->actingAs($this->createUser());

        $this->get('login');

        $this->assertRedirectedTo('/');
    }

    /**
     * @dataProvider getInvalidValues
     */
    public function testCannotLoginBecauseOfInvalidInput($value)
    {

        /**
         * Simulate a GET before POSTING.
         *This is to ensure stuffs like checking the path we were redirected to
         * rather than just HTTP status code
         */
        $this->get('login');

        $this->post('login', ['email' => $value]);

        $this->assertSessionHasErrors();
        $this->assertRedirectedTo('login');
    }

    public function testTokenIsSentToTheUserAfterFillingInTheFormSuccessfully()
    {
        $this->expectsEvents(AuthenticationLinkWasRequested::class);

        $user = $this->createUser();

        $this->get('login');

        $this->post('login', ['email' => $user->email]);

        $this->assertSessionHas('link.sent');
        $this->assertRedirectedTo('login');
    }

    public function testAUserIsNotLoggedInEvenAfterASuccessfulFormSubmission()
    {
        $this->expectsEvents(AuthenticationLinkWasRequested::class);

        $user = $this->createUser();

        $this->get('login');

        $this->post('login', ['email' => $user->email]);

        $this->assertSessionHas('link.sent');

        //Manually visit this page again.
        //If logged in, should redirect you to the profile page or something

        $this->get('login');
        $this->assertResponseOk();
    }

    public function getInvalidValues()
    {
        return [
            [
                ''
            ],
            [
                'me'
            ],
            [
                'you@you'
            ],
            [
                'roo.3'
            ]
        ];
    }
}
