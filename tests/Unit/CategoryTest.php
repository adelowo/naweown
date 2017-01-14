<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Naweown\Category;
use Tests\TestCase;

class CategoryTest extends TestCase
{

    use DatabaseMigrations;

    public function testFindBySlug()
    {
        $this->createCategory();

        $cat = Category::findBySlug('cat-slug');

        $this->assertNotNull($cat);
        $this->assertEquals('Category name', $cat->title);
    }

    public function testFindBySlugFailure()
    {
        $this->expectException(ModelNotFoundException::class);

        Category::findBySlug('oops');
    }

    protected function createCategory()
    {
        Category::create([
            'title' => "Category name",
            'description' => "Some cool category you should check out",
            'slug' => str_slug('cat-slug')
        ]);
    }
}
