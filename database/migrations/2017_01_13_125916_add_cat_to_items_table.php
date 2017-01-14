<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCatToItemsTable extends Migration
{
    public function up()
    {
        Schema::table("items", function (Blueprint $table) {
            $table->string('cats')
                ->default('uncategorized');
        });
    }

    public function down()
    {
        Schema::table("items", function (Blueprint $table) {
            $table->dropColumn('cats');
        });
    }
}
