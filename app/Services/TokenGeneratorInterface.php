<?php

namespace Naweown\Services;

interface TokenGeneratorInterface extends TokenCheckerInterface
{
    public function generate();
}
