<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultilingualCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('multilingual_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->json('title');
            $table->string('slug')->nullable();
            $table->json('description')->nullable();
            $table->json('intro')->nullable();
            $table->json('body')->nullable();
            $table->nullableTimestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('multilingual_categories');
    }
}
