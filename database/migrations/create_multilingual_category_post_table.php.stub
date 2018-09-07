<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultilingualCategoryPostTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('multilingual_category_post', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('post_id');
            $table->nullableTimestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('multilingual_category_post');
    }
}
