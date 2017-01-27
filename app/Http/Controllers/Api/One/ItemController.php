<?php

namespace Naweown\Http\Controllers\Api\One;

use Illuminate\Http\Request;
use Naweown\Http\Controllers\Controller;
use Naweown\Item;

class ItemController extends Controller
{

    const MAX_NUMBER_OF_ITEMS_PER_PAGE = 20;

    public function index(Request $request)
    {
        return Item::paginate(self::MAX_NUMBER_OF_ITEMS_PER_PAGE);
    }

    public function show(int $id)
    {
        return Item::findOrFail($id);
    }
}
