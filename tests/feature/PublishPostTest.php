<?php


namespace Dymantic\MultilingualPosts\Tests\feature;


use Dymantic\MultilingualPosts\Tests\MakesPosts;
use Dymantic\MultilingualPosts\Tests\TestCase;

class PublishPostTest extends TestCase
{
    use MakesPosts;
    /**
     *@test
     */
    public function a_post_may_be_made_into_a_published_post()
    {
        $this->withoutExceptionHandling();
        $post = $this->makePost();

        $response = $this->asLoggedInUser()->postJson("/multilingual-posts/published-posts", [
            "post_id" => $post->id
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('multilingual_posts', ['id' => $post->id, 'is_draft' => false]);
    }

    /**
     *@test
     */
    public function the_post_id_is_required_otherwise_response_is_404()
    {
        $post = $this->makePost();

        $response = $this->asLoggedInUser()->postJson("/multilingual-posts/published-posts", [
            "post_id" => null
        ]);
        $response->assertStatus(404);

        $this->assertDatabaseHas('multilingual_posts', ['id' => $post->id, 'is_draft' => true]);
    }
}