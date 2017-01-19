<?php

namespace Naweown\Http\Controllers;

use Illuminate\Http\Request;
use Naweown\User;

class UserRelationshipController extends Controller
{
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

    protected function followUser(
        Request $request,
        User $user
    ) {
        if ($this->isAFollower($user, $authenticatedUser = $request->user()) === null) {

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

    protected function isAFollower(
        User $user,
        $authenticatedUser
    ) {
        $isAFollower = $user->followers()
            ->where("follower_id", $authenticatedUser->id)
            ->first();

        return $isAFollower;
    }

    protected function unfollowUser(
        Request $request,
        User $user
    ) {
        if ($this->isAFollower($user, $authenticatedUser = $request->user()) === null) {
            return back()
                ->with(
                    'user.relationship.failed',
                    'You cannot unfollow a user you aren\'t following'
                );
        }

        $user->followers()->delete($authenticatedUser);

        if ($request->isJson()) {
            return [
                'user.unfollowed' => true
            ];
        }

        return redirect("@{$user->moniker}")
            ->with('user.unfollowed', true);
    }
}
