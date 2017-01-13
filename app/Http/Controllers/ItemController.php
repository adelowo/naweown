<?php

namespace Naweown\Http\Controllers;

use Illuminate\Contracts\Events\Dispatcher;
use Naweown\Events\ItemWasViewed;
use Naweown\Http\Requests\CreateItemRequest;
use Naweown\Http\Requests\UpdateItemRequest;
use Naweown\Item;

class ItemController extends Controller
{

    public function __construct()
    {
        $this->middleware(
            'auth',
            ['except' => ['index', 'show']]
        );

        $this->middleware('item_owner', [
            'only' => ['edit', 'update', 'destroy']
        ]);
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

        for ($i = 0; $i < 5; $i++) {
            if ($itemRequest->hasFile('images.' . $i)) {
                $files[] = $itemRequest->file('images.' . $i)
                    ->store('items');
            }
        }

        return $files;
    }

    public function show(
        Item $item,
        Dispatcher $dispatcher
    ) {
        $dispatcher->fire(new ItemWasViewed($item));

        return view('items.show', ['item' => $item]);
    }

    public function edit(Item $item)
    {
        return view('items.edit', ['item' => $item]);
    }

    public function update(UpdateItemRequest $updateItemRequest, Item $item)
    {
        $item->update([
            'slug' => $updateItemRequest->input('slug'),
            'description' => $updateItemRequest->input('description'),
            'title' => $updateItemRequest->input('title'),
            'images' => $this->getUpdatedFilePaths($updateItemRequest, $item)
        ]);

        return redirect()
            ->to(route('items.show', $item->id))
            ->with('item.updated', true);
    }

    protected function getUpdatedFilePaths(UpdateItemRequest $updateItemRequest, Item $item)
    {
        $files = [];

        $store = function (int $i) use ($updateItemRequest) {
            return $updateItemRequest->file('images.' . $i)
                ->store('items');
        };

        for ($i = 0; $i < 5; $i++) {
            if ($updateItemRequest->hasFile('images.' . $i)) {
                $files[] = $store($i);
            } else {
                if (isset($item->images[$i])) {
                    $files[] = $item->images[$i];
                }
            }
        }

        return $files;
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()
            ->to('profile')
            ->with('item.deleted', true);
    }
}
