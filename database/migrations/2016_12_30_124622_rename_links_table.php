<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameLinksTable extends Migration
{
    public function up()
    {
        Schema::table("links", function(BluePrint $table) {
           $table->rename('tokens');
        });
    }

    public function down()
    {
        Schema::table("tokens", function (Blueprint $table) {
           $table->rename('links');
        });
    }
}
