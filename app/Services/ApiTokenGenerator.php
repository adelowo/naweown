<?php

namespace Naweown\Services;

use Naweown\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApiTokenGenerator implements TokenGeneratorInterface
{

    public function generate()
    {
        $token = bin2hex(random_bytes(8));

        if ($this->isTokenAlreadyInUse($token)) {
            return $this->generate();
        }

        return $token;
    }

    public function isTokenAlreadyInUse(string $token)
    {
        try {

            User::findByApiToken($token);

            return true;
        } catch (ModelNotFoundException $e) {

            return false;
        }
    }
}
