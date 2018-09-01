<?php

namespace Dymantic\MultilingualPosts\Tests\Feature;

use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\PostResource;
use Dymantic\MultilingualPosts\Tests\TestCase;

class CreatePostTest extends TestCase
{
    /**
     *@test
     */
    public function a_post_can_be_created()
    {
        $this->withoutExceptionHandling();
        $post_data = [
            'title' => ['en' => 'Test Title', 'fr' => 'Test Titley'],
            'intro' => ['en' => 'Test Intro', 'fr' => 'Test Introy'],
            'description' => ['en' => 'Test Description', 'fr' => 'Test Descriptiony'],
        ];

        $response = $this->asLoggedInUser()->json("POST", "/multilingual-posts/", $post_data);
        $response->assertStatus(201);

        $this->assertDatabaseHasWithTranslations('multilingual_posts', $post_data);
    }

    /**
     *@test
     */
    public function successfully_creating_a_post_responds_with_new_model_data()
    {
        $this->withoutExceptionHandling();
        $post_data = [
            'title' => ['en' => 'Test Title', 'fr' => 'Test Titley'],
            'intro' => ['en' => 'Test Intro', 'fr' => 'Test Introy'],
            'description' => ['en' => 'Test Description', 'fr' => 'Test Descriptiony'],
        ];

        $response = $this->asLoggedInUser()->json("POST", "/multilingual-posts/", $post_data);
        $response->assertStatus(201);

        $this->assertCount(1, Post::all());
        $expected = (new PostResource(Post::first()))->toArray(request());

        $this->assertEquals($expected, $response->decodeResponseJson());
    }

    /**
     *@test
     */
    public function a_title_in_at_least_one_language_must_be_present_to_create_a_post()
    {
        $response = $this->asLoggedInUser()->json("POST", "/multilingual-posts/", ['title' => ""]);
        $response->assertStatus(422);

        $response->assertJsonValidationErrors('title');
    }

    /**
     *@test
     */
    public function the_title_must_be_an_array()
    {
        $response = $this->asLoggedInUser()->json("POST", "/multilingual-posts/", ['title' => "What language is this"]);
        $response->assertStatus(422);

        $response->assertJsonValidationErrors('title');
    }

    /**
     *@test
     */
    public function the_title_must_be_an_array_with_at_least_one_language_entry()
    {
        $response = $this->asLoggedInUser()->json("POST", "/multilingual-posts/", ['title' => ["what language is this"]]);
        $response->assertStatus(422);

        $response->assertJsonValidationErrors('title');
    }
}