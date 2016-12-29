<?php

namespace Naweown\Services;

/**
 * This interface is for the magic link (passwordless login) not some CSRNG
 * Interface TokenGeneratorInterface
 * @package Naweown\Services
 */
interface TokenGeneratorInterface
{
    public function generate();
}
