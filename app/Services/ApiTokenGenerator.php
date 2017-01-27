<?php

namespace Naweown\Services;

use Naweown\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApiTokenGenerator implements TokenGeneratorInterface
{

    public function generate()
    {
        $token = bin2hex(random_bytes(8));

        if ($this->isTokenAlreadyOwned($token)) {
            return $this->generate();
        }

        return $token;
    }

    protected function isTokenAlreadyOwned(string $token)
    {
        try {

            User::findByApiToken($token);

            return true;
        } catch (ModelNotFoundException $e) {

            return false;
        }
    }
}
