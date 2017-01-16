<?php

namespace Naweown\Http\Controllers;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Naweown\Events\UserProfileWasViewed;
use Naweown\User;

class UserController extends Controller
{

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

    public function relation(
        User $user,
        string $relationType
    ) {

        return view(
            "users.relationship",
            [
                'users' => $user->$relationType()
            ]
        );
    }

    public function relationAction(
        Request $request,
        User $user,
        string $action
    ) {

        $message = $action . 'User';

        return $this->$message($request, $user);
    }

    protected function followUser(Request $request, User $user)
    {

        $authenticatedUser = $request->user();

        $isAFollower = $user->followers()
            ->where("follower_id", $authenticatedUser->id)
            ->first();

        if ($isAFollower === null) {

            $authenticatedUser->follows()
                ->create([
                    'follower_id' => $authenticatedUser->id,
                    'user_id' => $user->id
                ]);

            if ($request->isJson()) {
                return [
                    'user.followed' => true
                ];
            }

            return redirect("@{$user->moniker}")
                ->with('user.followed', true);
        }

        return back()
            ->with(
                'user.relationship.failed',
                'Cannot follow the user as you are already following'
            );
    }

    protected function unfollowUser(Request $request, User $user)
    {
        $authenticatedUser = $request->user();

        $isAFollower = $user->followers()
            ->where("follower_id", $authenticatedUser->id)
            ->first();

        if ($isAFollower === null) {
            return back()
                ->with(
                    'user.relationship.failed',
                    'You cannot unfollow a user you aren\'t following'
                );
        }

        $user->followers()->delete($authenticatedUser->id);

        if ($request->isJson()) {
            return [
                'user.unfollowed' => true
            ];
        }

        return redirect("@{$user->moniker}")
            ->with('user.unfollowed', true);
    }
}
