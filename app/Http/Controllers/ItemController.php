<?php

namespace Naweown\Http\Controllers;

use Naweown\Http\Requests\CreateItemRequest;
use Naweown\Item;

class ItemController extends Controller
{

    public function __construct()
    {
        $this->middleware(
            'auth',
            ['except' => ['index', 'show']]
        );
    }

    public function index()
    {
        return view(
            "items.index",
            ['items' => Item::all()]
        );
    }

    public function create()
    {
        return view("items.create");
    }

    public function store(CreateItemRequest $itemRequest)
    {

        $files = $this->saveFiles($itemRequest);

        $slug = empty(trim($itemRequest->input('slug')))
            ?
            str_slug($itemRequest->input('title')) : $itemRequest->input('slug');

        $item = new Item([
            'title' => $itemRequest->input('title'),
            'slug' => $slug,
            'images' => $files,
            'description' => $itemRequest->input('description')
        ]);

        $itemRequest->user()->item()
            ->save($item);

        return redirect()
            ->route('items.show', $item->id)
            ->with('item.new', true);
    }

    protected function saveFiles(CreateItemRequest $itemRequest)
    {
        $files = [];

        for ($i = 0; $i <= 5; $i++) {
            if ($itemRequest->hasFile('images.' . $i)) {
                $files[] = $itemRequest->file('images.' . $i)
                    ->store('items');
            }
        }

        return $files;
    }

    public function show($id)
    {

        return view('items.show', ['item' => Item::findOrFail($id)]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
