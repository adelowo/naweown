<?php

namespace Naweown\Http\Controllers\Auth;

use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Naweown\Events\AuthenticationLinkWasRequested;
use Naweown\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Contracts\Events\Dispatcher;
use Naweown\Token;
use Naweown\User;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    protected $eventDispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->eventDispatcher = $dispatcher;
    }

    public function postlogin(Request $request)
    {

        $this->validate($request, [
            "email" => "required|email|exists:users"
        ]);

        $this->throttleLogin($request);

        $this->eventDispatcher->fire(
            new AuthenticationLinkWasRequested(
                User::findByEmailAddress($request->input('email')),
                (boolean)$request->input('remember')
            )
        );

        return redirect()
            ->route('login')
            ->with('link.sent', true);
    }

    protected function throttleLogin(Request $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        return $this->incrementLoginAttempts($request);
    }

    public function login(
        Request $request,
        AuthManager $authManager,
        Token $token
    ) {

        if ($token->isExpired()) {
            $token->delete();

            return redirect(route("login"))
                ->with('token.expired', true);
        }


        $shouldRemember = (bool)$request->query('remember', 0);

        $authManager->guard()->login($token->user, $shouldRemember);

        return $this->sendLoginResponse($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        return redirect()->intended();
    }
}
