<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Naweown\Token;
use Naweown\User;
use Tests\TestCase;

class TokenTest extends TestCase
{
    use DatabaseMigrations;

    public function testFindByToken()
    {
        $createdLink = $this->getCreatedToken();

        $token = Token::findByToken($createdLink->token);

        $this->assertEquals($createdLink->token, $token->token);
    }

    protected function getCreatedToken()
    {
        $user = $this->modelFactoryFor(User::class);

        return $user->token;
    }

    public function testTokenIsNotExpired()
    {
        $createdLink = $this->getCreatedToken();

        $token = Token::findByToken($createdLink->token);

        $this->assertFalse($token->isExpired());
    }

    public function testTokenIsExpired()
    {
        $createdLink = $this->getCreatedToken();


        $token = Token::findByToken($createdLink->token);

        $token->created_at =  $token->created_at->subMinutes(5);

        $token->save();

        $this->assertTrue($token->isExpired());
    }
}
