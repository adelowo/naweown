<?php

namespace Naweown\Http\Controllers;

use Illuminate\Http\Request;
use Naweown\User;

class ApiTokenController extends Controller
{

    public function token(Request $request, User $user)
    {
        if ($request->isMethod('POST')) {
            return $this->createToken($request, $user);
        }

        //Would always be a PUT
        return $this->updateToken($request, $user);
    }

    private function createToken(Request $request, User $user)
    {
        //If the user already own a token, do nothing, redirect him to the profile page.
        //The token should be viewable on the profile page anyways
        //ELSE
        //Create the token.

    }

    private function updateToken(Request $request, User $user)
    {

    }
}
