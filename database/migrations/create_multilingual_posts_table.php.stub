<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultilingualPostsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('multilingual_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('author_id')->nullable();
            $table->string('author_type')->nullable();
            $table->json('title');
            $table->string('slug')->nullable();
            $table->json('description')->nullable();
            $table->json('intro')->nullable();
            $table->json('body')->nullable();
            $table->date('first_published_on')->nullable();
            $table->date('publish_date')->nullable();
            $table->boolean('is_draft')->default(1);
            $table->nullableTimestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('multilingual_posts');
    }
}
