<?php

namespace Naweown\Http\Middleware;

use Closure;

class CanEditProfile
{

    public function handle($request, Closure $next)
    {
        $moniker = str_replace_first("@", "", $request->segment(1));

        abort_if($request->user()->moniker !== $moniker, 404);

        return $next($request);
    }
}
