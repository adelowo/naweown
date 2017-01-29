<?php

namespace Naweown\Http\Controllers;

use Illuminate\Http\Request;
use Naweown\Services\ApiTokenGenerator;
use Naweown\User;

class ApiTokenController extends Controller
{

    protected $generator;

    public function __construct(ApiTokenGenerator $tokenGenerator)
    {
        $this->generator = $tokenGenerator;
    }

    public function token(Request $request, User $user)
    {
        if ($request->isMethod('POST')) {
            return $this->createToken($request, $user);
        }

        //Would always be a PUT
        return $this->updateToken($request, $user);
    }

    private function createToken(
        Request $request,
        User $user
    )
    {

        if ($user->hasApiToken()) {
            return back()
                ->with(
                    'token.creation.failed',
                    'You already have a token. 
                    If you need a new one, do request for an updated one.
                    You cannot create two api tokens'
                );
        }

        $user->createToken($this->generator->generate());

        return redirect()
            ->route('users.profile', $user->moniker)
            ->with(
                'token.creation.success',
                'Your token was created successfully'
            );
    }

    private function updateToken(
        Request $request,
        User $user
    )
    {

        $redirectResponse = redirect()
            ->route('users.profile', $user->moniker);

        if (!$user->hasApiToken()) {
            return $redirectResponse; //no session flashing cos you a troll
        }

        $user->updateToken($this->generator->generate());

        return $redirectResponse->with(
            'token.updated',
            true
        );
    }
}
