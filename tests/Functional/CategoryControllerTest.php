<?php

namespace Tests\Functional;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Naweown\Category;
use Naweown\Events\CategoryWasViewed;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{

    use DatabaseMigrations;

    public function testPageIsUpAndRunning()
    {
        $this->get('category');
        $this->assertResponseOk();
    }

    public function testSingleCategoryPageIsUpAndRunning()
    {
        Category::create([
            'title' => "Wood work",
            'slug' => "wood-work",
            'description' => "Snappie snap snapped"
        ]);

        $this->expectsEvents(CategoryWasViewed::class);

        $this->get('category/wood-work');
        $this->assertResponseOk();
    }

}
