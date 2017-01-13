<?php

namespace Naweown\Http\Middleware;

use Closure;
use Naweown\Item;
use Illuminate\Http\Request;

class CanEditPost
{
    public function handle(Request $request, Closure $next)
    {
        $item = Item::find($request->segment(2));

        abort_if($request->user()->id !== $item->user_id, 403);

        return $next($request);
    }
}
