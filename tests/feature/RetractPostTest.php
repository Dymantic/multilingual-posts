<?php


namespace Dymantic\MultilingualPosts\Tests\feature;


use Dymantic\MultilingualPosts\Tests\MakesPosts;
use Dymantic\MultilingualPosts\Tests\TestCase;

class RetractPostTest extends TestCase
{
    use MakesPosts;

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
}