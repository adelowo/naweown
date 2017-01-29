<?php

namespace Naweown\Services;

interface TokenCheckerInterface
{

    public function isTokenAlreadyInUse(string $token);
}
