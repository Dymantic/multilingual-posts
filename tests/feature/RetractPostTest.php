<?php


namespace Dymantic\MultilingualPosts\Tests\feature;


use Dymantic\MultilingualPosts\PostResource;
use Dymantic\MultilingualPosts\Tests\ComparesResources;
use Dymantic\MultilingualPosts\Tests\UsesModels;
use Dymantic\MultilingualPosts\Tests\TestCase;

class RetractPostTest extends TestCase
{
    use UsesModels, ComparesResources;

    /**
     *@test
     */
    public function a_published_post_may_be_retracted()
    {
        $this->withoutExceptionHandling();

        $post = $this->makePost();
        $post->publish();

        $response = $this->asLoggedInUser()->deleteJson("/multilingual-posts/published-posts/{$post->id}");
        $response->assertStatus(200);

        $this->assertDatabaseHas('multilingual_posts', ['id' => $post->id, 'is_draft' => true]);
    }

    /**
     *@test
     */
    public function retracting_a_post_responds_with_the_fresh_post_data()
    {
        $this->withoutExceptionHandling();

        $post = $this->makePost();
        $post->publish();

        $response = $this->asLoggedInUser()->deleteJson("/multilingual-posts/published-posts/{$post->id}");
        $response->assertStatus(200);
        $expected = $this->getResourceResponseData(new PostResource($post->fresh()));
        $this->assertEquals($expected, $response->decodeResponseJson());
    }
}