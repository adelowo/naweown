<?php

namespace Naweown\Services;

class MagicLinkTokenGenerator implements TokenGeneratorInterface
{

    public function generate()
    {
        return str_random();
    }
}
