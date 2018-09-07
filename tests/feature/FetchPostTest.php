<?php


namespace Dymantic\MultilingualPosts\Tests\feature;


use Dymantic\MultilingualPosts\PostResource;
use Dymantic\MultilingualPosts\Tests\UsesModels;
use Dymantic\MultilingualPosts\Tests\TestCase;

class FetchPostTest extends TestCase
{
    use UsesModels;
    
    /**
     *@test
     */
    public function a_post_can_be_fetched_as_a_post_resource()
    {
        $this->withoutExceptionHandling();
        $post = $this->makePost();

        $response = $this->asLoggedInUser()->getJson("/multilingual-posts/posts/{$post->id}");
        $response->assertStatus(200);

        $expected = (new PostResource($post))->toArray(request());

        $this->assertEquals($expected, $response->decodeResponseJson());
    }
}