<?php

namespace Naweown\Http\Controllers\Auth;

use Naweown\Http\Requests;
use Naweown\Http\Controllers\Controller;
use Naweown\Token;
use function Naweown\carbon;

class AccountActivationController extends Controller
{

    public function activate(Token $token)
    {
        $redirectRoute = route("users.profile", $token->user->moniker);

        if ($token->isExpired()) {
            $token->delete();

            return redirect($redirectRoute)
                ->with(
                    'token.expired',
                    'This token has expired and has thus been invalidated.
                    Please request for another token to activate your account'
                );
        }

        $token->user->activateAccount();

        return redirect($redirectRoute)
            ->with(
                'account.activated',
                "Your email address has been verified"
            );
    }
}
