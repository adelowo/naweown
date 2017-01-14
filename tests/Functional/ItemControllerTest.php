<?php

namespace Tests\Functional;

use Naweown\Category;
use Naweown\Item;
use Naweown\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ItemControllerTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        Category::create([
            'title' => "The dark path",
            'slug' => "dark-force",
            'description' => "Join Darth Sidious on his mission to destroy all Jedis"
        ]);
    }

    public function testPageIsUpAndRunning()
    {
        $this->get('items');
        $this->assertResponseOk();
    }

    public function testItemsCanOnlyBeAddedByUsersWithAnActivatedAccount()
    {
        $this->be($this->modelFactoryFor(User::class));

        $this->post('items', $this->getValidData());

        $this->assertResponseStatus(403); //access forbidden
    }

    public function getValidData()
    {
        $validData = [
            'title' => "Some cool title",
            'description' => "THe return of the Jedi",
            'cats' => 'dark-force',
            'slug' => 'oops-oops'
        ];

        return $validData;
    }

    public function testSlugIsAutoGeneratedIfItWasNotProvided()
    {

        $user = $this->modelFactoryFor(User::class);
        $user->activateAccount();

        $this->be($user);

        $validData = $this->getValidData();

        array_pop($validData); //remove the 'slug'index from the array

        $expectedSlug = str_slug($validData['title']);

        $this->post('items', $validData);

        $this->assertRedirectedTo('items/1');
        $this->seeInDatabase('items', ['slug' => $expectedSlug]);
    }

    public function testSlugIsNotGeneratedIfOneIsProvided()
    {
        $user = $this->modelFactoryFor(User::class);
        $user->activateAccount();

        $this->be($user);

        $validData = $this->getValidData();

        $this->post('items', $validData);

        $this->assertRedirectedTo('items/1');
        $this->seeInDatabase('items', ['slug' => array_pop($validData)]);
    }

    public function testItemCanOnlyBeModifiedByItsCreator()
    {
        //Modification here refers to deletions and updates

        $itemOwner = $this->modelFactoryFor(User::class);

        $item = $this->saveItem($itemOwner);

        $troll = $this->modelFactoryFor(User::class);

        $troll->activateAccount();

        $this->be($troll);

        $id = $item->id;

        $this->get("items/{$id}/edit");
        $this->assertResponseStatus(403);

        $this->put("items/{$id}", $this->getValidData());
        $this->assertResponseStatus(403);
        //make sure nothing gets updated
        $this->seeInDatabase('items', ['id' => $id, 'slug' => $item->slug]);

        $this->delete("items/{$id}");
        $this->assertResponseStatus(403);
        //make sure nothing was deleted
        $this->seeInDatabase('items', ['id' => $id]);
    }

    protected function saveItem(User $user)
    {
        $images = json_encode([
            "items/231bb5b1bcfb7ab57252f6abfacfaef4.jpeg",
            "pics/12c8b198aed6c43c0a7465b22132afb4.jpeg",
            "pics/45bbb2268dbfa0623a9409fd54b76b3a.jpeg",
            "items/bfe68e719921664602dd950e458d5243.jpeg",
        ]);

        $model = new Item([
            'title' => "The return of the Jedi",
            'slug' => "jedis-resurrection",
            'description' => "Darth Vader is dead. Long live Luke SkyWalker",
            'images' => $images
        ]);

        $user->item()->save($model);

        return $model;
    }

    public function testItemCanBeUpdated()
    {
        $user = $this->modelFactoryFor(User::class);

        $item = $this->saveItem($user);

        $user->activateAccount();

        $this->be($user);

        $this->put("items/{$item->id}", $this->getValidData());

        $this->assertRedirectedTo("items/{$item->id}");
        $this->assertResponseStatus(302);


        $this->seeInDatabase(
            'items',
            [
                'title' => $this->getValidData()['title'],
                'cats' => 'uncategorized,dark-force'
            ]
        );

        $this->dontSeeInDatabase('items', ['title' => $item->title]);
    }

    public function testItemsCanBeCreatedWithCategories()
    {

        $user = $this->modelFactoryFor(User::class);

        $user->activateAccount();

        $this->be($user);

        $this->post("items", $this->getValidData());

        $this->assertRedirectedTo("items/1");
        $this->assertResponseStatus(302);

        $this->seeInDatabase(
            'items',
            [
                'title' => $this->getValidData()['title'],
                'cats' => 'dark-force'
            ]
        );
    }
}
