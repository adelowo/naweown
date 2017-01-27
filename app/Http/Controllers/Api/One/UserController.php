<?php

namespace Naweown\Http\Controllers\Api\One;

use Illuminate\Http\Request;
use Naweown\Http\Controllers\Controller;
use Naweown\User;

class UserController extends Controller
{

    const NUMBER_OF_USERS_PER_PAGE = 20;

    public function index(Request $request)
    {

        return User::paginate(self::NUMBER_OF_USERS_PER_PAGE);
    }

    public function profile(string $moniker)
    {
        return User::findByMoniker($moniker);
    }

    public function items(string $moniker)
    {
        return User::findByMoniker($moniker)->item;
    }
}