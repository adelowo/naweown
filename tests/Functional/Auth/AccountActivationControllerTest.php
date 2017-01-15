<?php

namespace Tests\Functional\Auth;

use Naweown\Token;
use Naweown\User;
use Tests\TestCase;
use function Naweown\carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AccountActivationControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testCannotActivateAnAccountWithANonExistentToken()
    {
        $this->get("account/activate/ss");

        $this->assertResponseStatus(404);
    }

    public function testCannotActivateAnAccountWithAnExpiredToken()
    {

        $user = $this->modelFactoryFor(User::class);

        //Manually set the stage for failure, set the token date to 6 minutes past the current time
        Token::whereUserId(1)
            ->update(['created_at' => carbon()->subMinutes(6)]);

        $this->get("account/activate/{$user->token->token}");

        $this->assertSessionHas('token.expired');

        $this->assertRedirectedToRoute('users.profile', $user->moniker);
    }

    public function testExpiredTokensAreAutoDeletedWhenUsed()
    {
        $user = $this->modelFactoryFor(User::class);

        $token = $user->token->token;

        Token::findByToken($token)
            ->update(['created_at' => carbon()->subMinutes(5)]);

        $this->get("account/activate/{$token}");

        $this->dontSeeInDatabase('tokens', ['token' => $token]);
    }

    public function testAnAccountWasSuccessfullyActivated()
    {
        $user = $this->modelFactoryFor(User::class);

        $this->actingAs($user);

        $this->get("account/activate/{$user->token->token}");

        $this->dontSeeInDatabase('tokens', ['user_id' => 1]);
        $this->assertSessionMissing('token.expired');
        $this->assertSessionHas('account.activated');
        $this->assertRedirectedToRoute('users.profile', $user->moniker);
    }

    public function testAnAccountWasSuccessfullyActivatedEvenIfTheUserIsNotLoggedIn()
    {
        $user = $this->modelFactoryFor(User::class);

        $this->get("account/activate/{$user->token->token}");

        $this->dontSeeInDatabase('tokens', ['user_id' => 1]);
        $this->assertSessionMissing('token.expired');
        $this->assertSessionHas('account.activated');
        $this->assertRedirectedToRoute('users.profile', $user->moniker);
    }

    public function testOnlyUnActivatedAccountsCanBeActivated()
    {
        //Delete the token for this user
        //After which we set the `is_email_validated` property to "true"
        //Then a 404 error MUST be thrown since we do not have this token anymore

        $user = $this->modelFactoryFor(User::class);

        $token = $user->token->token;

        $user->token()->delete();
        $user->update(['is_email_validated' => User::EMAIL_VALIDATED]);

        $this->get("account/activate/{$token}");
        $this->assertResponseStatus(404);
    }
}
