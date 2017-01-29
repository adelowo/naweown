<?php

namespace Naweown\Services;

use Naweown\Token;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MagicLinkTokenGenerator implements TokenGeneratorInterface
{

    public function generate()
    {
        $length = [4, 8, 16, 32];

        $token = str_random($length[array_rand($length)]);

        if ($this->isTokenAlreadyInUse($token)) {
            return $this->generate();
        }

        return $token;
    }

    public function isTokenAlreadyInUse(string $token)
    {
        try {
            Token::findByToken($token);

            return true;
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
}
