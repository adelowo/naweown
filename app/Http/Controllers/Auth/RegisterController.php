<?php

namespace Naweown\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Naweown\Events\AccountActivationLinkWasRequested;
use Naweown\Events\AuthenticationLinkWasRequested;
use Naweown\Events\UserWasCreated;
use Naweown\User;
use Validator;
use Naweown\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Contracts\Events\Dispatcher;
use Naweown\Notifications\SendAccountActivationLink;

class RegisterController extends Controller
{

    use RegistersUsers;

    protected $redirectTo = '/';

    protected $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->middleware('guest');
        $this->dispatcher = $dispatcher;
    }

    public function register(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email|max:255|unique:users',
            'moniker' => 'required|string|min:4|max:255|unique:users',
            "name" => 'required|string|min:4|max:255'
        ]);

        $this->guard()->login($user = $this->create($request->all()));

        $this->dispatcher->fire(new UserWasCreated($user));

        $this->dispatcher->fire(new AccountActivationLinkWasRequested($user));

        return redirect(route("home"));
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            "moniker" => $data['moniker'],
            "bio" => "Just a local (cfr)art Lover"
        ]);
    }
}
