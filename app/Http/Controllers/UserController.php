<?php

namespace Naweown\Http\Controllers;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Naweown\Events\UserAccountWasDeleted;
use Naweown\Events\UserProfileWasUpdated;
use Naweown\Events\UserProfileWasViewed;
use Naweown\User;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(function (Request $request, \Closure $next) {

            $moniker = str_replace_first("@", "", $request->segment(1));

            abort_if($request->user()->moniker !== $moniker, 404);

            return $next($request);
        }, ['only' => 'destroy']);
    }

    public function index()
    {
        return view(
            'users.all',
            [
                'users' => User::paginate(50)
            ]
        );
    }

    public function show(
        Request $request,
        User $user,
        Dispatcher $dispatcher
    ) {

        $isOwner = false;

        if ($request->user() !== null) {
            $isOwner = $request->user()->id === $user->id;
        }

        $dispatcher->fire(new UserProfileWasViewed($user));

        return view(
            'users.show',
            [
                'user' => $user,
                'isOwner' => $isOwner
            ]
        );
    }

    public function destroy(
        User $user,
        Dispatcher $dispatcher
    ) {
        $user->delete();

        $dispatcher->fire(new UserAccountWasDeleted($user));

        return redirect('logout');
    }

    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user
        ]);
    }

    public function update(
        Request $request,
        User $user,
        Dispatcher $dispatcher
    ) {

        $emailUnique = Rule::unique('users', 'email')
            ->ignore($user->id);

        $monikerUnique = Rule::unique('users', 'moniker')
            ->ignore($user->id);

        $this->validate($request, [
            'email' => "required|email|max:255|$emailUnique",
            'moniker' => "required|string|min:4|max:255|$monikerUnique",
            "name" => 'required|string|min:4|max:255',
            "bio" => 'required|string|min:4|max:1000'
        ]);

        $user->updateProfile($request->only(['email', 'moniker', 'name', 'bio']));

        $dispatcher->fire(new UserProfileWasUpdated($user));

        return redirect("@{$user->moniker}")
            ->with('profile.updated', true);
    }
}
