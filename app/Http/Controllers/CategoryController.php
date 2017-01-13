<?php

namespace Naweown\Http\Controllers;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Naweown\Category;
use Naweown\Events\CategoryWasViewed;

class CategoryController extends Controller
{

    public function index()
    {
        return view(
            'categories.index',
            ['cats' => Category::all()]
        );
    }

    public function show(Category $category, Dispatcher $dispatcher)
    {
        $dispatcher->fire(new CategoryWasViewed($category));

        return view(
            'categories.show',
            ['cat' => $category]
        );
    }
}
