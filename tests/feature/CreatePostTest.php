<?php

namespace Dymantic\MultilingualPosts\Tests\Feature;

use Dymantic\MultilingualPosts\Post;
use Dymantic\MultilingualPosts\PostResource;
use Dymantic\MultilingualPosts\Tests\TestCase;
use Dymantic\MultilingualPosts\Tests\UsesModels;

class CreatePostTest extends TestCase
{
    use UsesModels;

    /**
     * @test
     */
    public function a_post_can_be_created()
    {
        $category = $this->makeCategory();
        $this->withoutExceptionHandling();
        $post_data = [
            'title'       => ['en' => 'Test Title', 'fr' => 'Test Titley'],
            'intro'       => ['en' => 'Test Intro', 'fr' => 'Test Introy'],
            'description' => ['en' => 'Test Description', 'fr' => 'Test Descriptiony'],
        ];

        $response = $this->asLoggedInUser()
                         ->json("POST", "/multilingual-posts/posts/",
                             array_merge($post_data, ['category_id' => $category->id]));
        $response->assertStatus(201);

        $this->assertDatabaseHasWithTranslations('multilingual_posts', $post_data);

        $this->assertCount(1, Post::all());
        $post = Post::first();

        $this->assertTrue($post->categories->contains($category));
    }

    /**
     * @test
     */
    public function successfully_creating_a_post_responds_with_new_model_data()
    {
        $this->withoutExceptionHandling();
        $post_data = [
            'title'       => ['en' => 'Test Title', 'fr' => 'Test Titley'],
            'intro'       => ['en' => 'Test Intro', 'fr' => 'Test Introy'],
            'description' => ['en' => 'Test Description', 'fr' => 'Test Descriptiony'],
        ];

        $response = $this->asLoggedInUser()->json("POST", "/multilingual-posts/posts/", $post_data);
        $response->assertStatus(201);

        $this->assertCount(1, Post::all());
        $expected = (new PostResource(Post::first()))->toArray(request());

        $this->assertEquals($expected, $response->decodeResponseJson());
    }

    /**
     * @test
     */
    public function a_title_in_at_least_one_language_must_be_present_to_create_a_post()
    {
        $response = $this->asLoggedInUser()->json("POST", "/multilingual-posts/posts/", ['title' => ""]);
        $response->assertStatus(422);

        $response->assertJsonValidationErrors('title');
    }

    /**
     * @test
     */
    public function the_title_must_be_an_array()
    {
        $response = $this->asLoggedInUser()->json("POST", "/multilingual-posts/posts/",
            ['title' => "What language is this"]);
        $response->assertStatus(422);

        $response->assertJsonValidationErrors('title');
    }

    /**
     * @test
     */
    public function the_title_must_be_an_array_with_at_least_one_language_entry()
    {
        $response = $this->asLoggedInUser()->json("POST", "/multilingual-posts/posts/",
            ['title' => ["what language is this"]]);
        $response->assertStatus(422);

        $response->assertJsonValidationErrors('title');
    }

}