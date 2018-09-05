<?php


namespace Dymantic\MultilingualPosts\Tests\feature;


use Dymantic\MultilingualPosts\Tests\MakesPosts;
use Dymantic\MultilingualPosts\Tests\TestCase;

class DeletePostTest extends TestCase
{
    use MakesPosts;

    /**
     *@test
     */
    public function a_post_may_be_deleted()
    {
        $this->withoutExceptionHandling();
        $post = $this->makePost();

        $response = $this->asLoggedInUser()->deleteJson("/multilingual-posts/posts/{$post->id}");
        $response->assertStatus(200);

        $this->assertDatabaseMissing('multilingual_posts', ['id' => $post->id]);
    }
}