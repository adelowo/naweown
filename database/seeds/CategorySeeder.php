<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Naweown\Category::create([
            'title' => 'Wood work',
            'description' => "Whoopie do whoop",
            "slug" => "wood-work"
        ]);

        \Naweown\Category::create([
            'title' => 'Wood works',
            'description' => "Whoopie do whoop",
            "slug" => "wood-works"
        ]);

        \Naweown\Category::create([
            'title' => 'something else',
            'description' => "Whoopie do whoop",
            "slug" => "something-else"
        ]);

    }
}
