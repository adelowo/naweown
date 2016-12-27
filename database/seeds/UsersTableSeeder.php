<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Naweown\User::create([
            "name" => "Some Admin",
            "moniker" => "supercow",
            "email" => "root@app.com",
            "bio" => "I have got super cow powers in here"
        ]);
    }
}
