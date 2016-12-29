<?php

namespace Naweown\Services;

class TokenGenerator implements TokenGeneratorInterface
{

    public function generate()
    {
        return str_random();
    }
}
