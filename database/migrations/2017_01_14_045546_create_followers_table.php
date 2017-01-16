<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowersTable extends Migration
{
    public function up()
    {
        Schema::create('followers', function (Blueprint $table) {

            $table->increments('id');

            $table->unsignedInteger('user_id');

            $table->foreign("user_id")
                ->references("id")
                ->on("users");

            $table->unsignedInteger('follower_id');

            $table->foreign('follower_id')
                ->references('id')
                ->on("users");

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('followers');
    }
}
