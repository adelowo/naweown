<?php

namespace Naweown\Http\Controllers\Auth;

use Illuminate\Http\Request;
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
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    protected $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->middleware('guest');
        $this->dispatcher = $dispatcher;
    }

    public function register(Request $request)
    {
        var_dump($request->all());

        $this->validate($request, [
            'email' => 'required|email|max:255|unique:users',
            'moniker' => 'required|string|min:4|max:255|unique:users',
            "name" => 'required|string|min:4|max:255'
        ]);

        $this->guard()->login($user = $this->create($request->all()));

        $this->dispatcher->fire(new UserWasCreated($user));

        $user->notify(new SendAccountActivationLink($user));

        return redirect(route("home"));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
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
