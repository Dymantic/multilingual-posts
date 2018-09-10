<?php


namespace Dymantic\MultilingualPosts\Tests\feature;


use Dymantic\MultilingualPosts\PostResource;
use Dymantic\MultilingualPosts\Tests\ComparesResources;
use Dymantic\MultilingualPosts\Tests\UsesModels;
use Dymantic\MultilingualPosts\Tests\TestCase;

class FetchPaginatedPostsTest extends TestCase
{
    use UsesModels, ComparesResources;

    /**
     * @test
     */
    public function a_paginated_index_of_posts_can_be_fetched()
    {
        $this->withoutExceptionHandling();
        $titles = [
            "One",
            "Two",
            "Three",
            "Four",
            "Five",
            "Six",
            "Seven",
            "Eight",
            "Nine",
            "Ten",
            "Eleven",
            "Twelve",
            "Thirteen",
            "Fourteen",
            "Fifteen",
            "Sixteen",
            "Seventeen"
        ];
        $posts = collect($titles)->map(function ($title) {
            return $this->makePost(['title' => ['en' => $title, 'fr' => 'French ' . $title]]);
        });

        $response = $this->asLoggedInUser()->getJson("/multilingual-posts/posts");
        $response->assertStatus(200);

        $expected_posts_data = $posts->take(15)->map(function ($post) {
            return $this->getResourceResponseData(new PostResource($post));
        })->all();

        $this->assertEquals($expected_posts_data, $response->decodeResponseJson('data'));
        $this->assertEquals(1, $response->decodeResponseJson('meta')['current_page']);
        $this->assertEquals(2, $response->decodeResponseJson('meta')['last_page']);
    }
}